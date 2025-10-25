<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GoogleDriveToken extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_in',
        'expires_at',
        'token_type',
        'scope',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if token is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Check if token needs refresh (expires in 5 minutes)
     */
    public function needsRefresh(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return Carbon::now()->addMinutes(5)->greaterThan($this->expires_at);
    }
}
