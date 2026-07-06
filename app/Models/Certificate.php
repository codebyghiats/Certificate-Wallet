<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'issuer',
        'file_path',
        'file_type',
        'expired_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'date',
        ];
    }

    /**
     * Get the user that owns this certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the categories of this certificate.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Check if the certificate is expired.
     */
    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    /**
     * Check if the certificate expires within N days.
     */
    public function isExpiringSoon(int $days = 30): bool
    {
        if (!$this->expired_at || $this->isExpired()) {
            return false;
        }

        return $this->expired_at->diffInDays(now()) <= $days;
    }

    /**
     * Get the expiry status: 'expired', 'expiring_soon', 'active', or 'no_expiry'.
     */
    public function getExpiryStatus(): string
    {
        if (!$this->expired_at) {
            return 'no_expiry';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isExpiringSoon()) {
            return 'expiring_soon';
        }

        return 'active';
    }
}
