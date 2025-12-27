<?php

namespace App\Http\Controllers;

use App\Models\GoogleDriveToken;
use Illuminate\Http\Request;
use Google_Client;
use Carbon\Carbon;
use Exception;

class GoogleDriveOAuthController extends Controller
{
    /**
     * Redirect to Google OAuth consent screen
     */
    public function redirect()
    {
        try {
            $client = $this->getClient();
            $authUrl = $client->createAuthUrl();

            return redirect($authUrl);
        } catch (Exception $e) {
            \Log::error('Google Drive OAuth redirect error: ' . $e->getMessage());
            \Log::error('Credentials path: ' . config('google-drive.oauth_credentials_path'));
            \Log::error('Redirect URI: ' . config('google-drive.redirect_uri'));

            return back()->with('error', 'Failed to initialize Google Drive connection: ' . $e->getMessage());
        }
    }

    /**
     * Handle OAuth callback from Google
     */
    public function callback(Request $request)
    {
        // Log all request parameters for debugging
        \Log::info('Google OAuth Callback received', [
            'all_params' => $request->all(),
            'has_code' => $request->has('code'),
            'has_error' => $request->has('error'),
            'full_url' => $request->fullUrl(),
            'redirect_uri_config' => config('google-drive.redirect_uri'),
        ]);

        // Determine redirect route
        if (auth()->check()) {
            $role = auth()->user()->role === 'super_admin' ? 'admin' : auth()->user()->role;
            $redirectRoute = $role . '.dashboard';
        } else {
            $redirectRoute = 'login';
        }

        if ($request->has('error')) {
            \Log::error('Google OAuth error response', [
                'error' => $request->error,
                'error_description' => $request->error_description ?? 'No description',
            ]);
            return redirect()->route($redirectRoute)
                ->with('error', 'Google Drive connection was denied: ' . ($request->error_description ?? $request->error));
        }

        if (!$request->has('code')) {
            \Log::error('No authorization code in callback', [
                'request_params' => $request->all(),
            ]);
            return redirect()->route($redirectRoute)
                ->with('error', 'No authorization code received from Google. Please check redirect URI configuration.');
        }

        try {
            $client = $this->getClient();

            // Exchange authorization code for access token
            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            if (isset($token['error'])) {
                throw new Exception($token['error_description'] ?? 'Failed to get access token');
            }

            // Save shared token to database (user_id = null for shared access)
            GoogleDriveToken::updateOrCreate(
                ['user_id' => null], // Shared token for all users
                [
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'] ?? null,
                    'expires_in' => $token['expires_in'] ?? null,
                    'expires_at' => isset($token['expires_in'])
                        ? Carbon::now()->addSeconds($token['expires_in'])
                        : null,
                    'token_type' => $token['token_type'] ?? 'Bearer',
                    'scope' => $token['scope'] ?? null,
                ]
            );

            return redirect()->route($redirectRoute)
                ->with('success', 'Google Drive connected successfully! You can now upload files to Google Drive.');

        } catch (Exception $e) {
            \Log::error('Google Drive OAuth callback error: ' . $e->getMessage());
            \Log::error('Token response: ' . json_encode($token ?? []));

            return redirect()->route($redirectRoute)
                ->with('error', 'Failed to connect Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect Google Drive (admin only)
     */
    public function disconnect()
    {
        try {
            // Get shared token
            $tokenRecord = GoogleDriveToken::whereNull('user_id')->first();

            if ($tokenRecord) {
                // Revoke token from Google
                try {
                    $client = $this->getClient();
                    $client->revokeToken($tokenRecord->access_token);
                } catch (Exception $e) {
                    \Log::warning('Failed to revoke Google token: ' . $e->getMessage());
                }

                // Delete from database
                $tokenRecord->delete();
            }

            return back()->with('success', 'Google Drive disconnected successfully.');

        } catch (Exception $e) {
            return back()->with('error', 'Failed to disconnect Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Get connection status
     */
    public function status()
    {
        // Get shared token (user_id = null)
        $tokenRecord = GoogleDriveToken::whereNull('user_id')->first();

        $status = [
            'connected' => $tokenRecord !== null,
            'user_email' => null,
            'expires_at' => null,
            'needs_reconnect' => false,
        ];

        if ($tokenRecord) {
            $status['expires_at'] = $tokenRecord->expires_at?->format('Y-m-d H:i:s');
            $status['needs_reconnect'] = $tokenRecord->isExpired() && !$tokenRecord->refresh_token;

            // Try to get user email from Google
            try {
                $client = $this->getClient();
                $client->setAccessToken([
                    'access_token' => $tokenRecord->access_token,
                    'refresh_token' => $tokenRecord->refresh_token,
                ]);

                if (!$tokenRecord->isExpired()) {
                    $oauth2 = new \Google_Service_Oauth2($client);
                    $userInfo = $oauth2->userinfo->get();
                    $status['user_email'] = $userInfo->email;
                }
            } catch (Exception $e) {
                \Log::warning('Failed to get Google user info: ' . $e->getMessage());
            }
        }

        return response()->json($status);
    }

    /**
     * Initialize Google Client
     */
    protected function getClient()
    {
        $credentialsPath = base_path(config('google-drive.oauth_credentials_path'));

        if (!file_exists($credentialsPath)) {
            throw new Exception('OAuth credentials file not found');
        }

        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->addScope(\Google_Service_Drive::DRIVE_FILE);
        $client->setRedirectUri(config('google-drive.redirect_uri'));

        return $client;
    }
}
