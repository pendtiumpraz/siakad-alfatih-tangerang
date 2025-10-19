<?php

namespace App\View\Composers;

use App\Models\Pengumuman;
use App\Models\Mahasiswa;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();

        if (!$user) {
            $view->with('recentPengumumans', collect());
            $view->with('unreadCount', 0);
            return;
        }

        // Handle mahasiswa differently - they need read tracking
        if ($user->role === 'mahasiswa') {
            $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

            // Always set mahasiswa variable
            $view->with('mahasiswa', $mahasiswa);

            if (!$mahasiswa) {
                $view->with('recentPengumumans', collect());
                $view->with('unreadCount', 0);
                return;
            }

            // Get active pengumumans for mahasiswa with read status
            $recentPengumumans = Pengumuman::active()
                ->forMahasiswa()
                ->with(['pembuat', 'reads' => function($query) use ($mahasiswa) {
                    $query->where('mahasiswa_id', $mahasiswa->id);
                }])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Count unread (those without read record)
            $unreadCount = $recentPengumumans->filter(function($pengumuman) {
                return $pengumuman->reads->isEmpty();
            })->count();

            $view->with('recentPengumumans', $recentPengumumans);
            $view->with('unreadCount', $unreadCount);
        } else {
            // For admin/dosen/operator - show all active pengumumans
            $recentPengumumans = Pengumuman::active()
                ->where('untuk_mahasiswa', true)
                ->with('pembuat')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $unreadCount = $recentPengumumans->count();

            $view->with('recentPengumumans', $recentPengumumans);
            $view->with('unreadCount', $unreadCount);
        }
    }
}
