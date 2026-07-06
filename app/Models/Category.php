<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['name', 'icon'];

    /**
     * Get all certificates belonging to this category.
     */
    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class);
    }
}
