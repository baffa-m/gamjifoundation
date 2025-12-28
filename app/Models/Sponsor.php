<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_name',
        'sponsor_type',
        'registration_number',
        'description',
        'website',
        'address',
        'verification_status',
        'verification_notes',
        'verified_at'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function awards()
    {
        return $this->hasMany(Award::class);
    }
}