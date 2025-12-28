<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone_number',
        'address',
        'date_of_birth',
        'gender',
        'state_of_origin',
        'lga',
        'school_name',
        'jamb_reg_number',
        'waec_reg_number',
        'waec_results',
        'profile_picture'
    ];

    protected $casts = [
        'waec_results' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicantDocument::class);
    }

    public function getJambDocument()
    {
        return $this->documents()->where('type', 'jamb')->first();
    }

    public function getWaecDocument()
    {
        return $this->documents()->where('type', 'waec')->first();
    }

    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }


    public function scopeByFullName($query, $fullName)
    {
        return $query->where('full_name', 'like', '%' . $fullName . '%');
    }

    public function scopeByStateOfOrigin($query, $stateOfOrigin)
    {
        return $query->where('state_of_origin', $stateOfOrigin);
    }

    public function scopeByLga($query, $lga)
    {
        return $query->where('lga', $lga);
    }

    public function scopeByJambScore($query, $minScore, $maxScore)
    {
        return $query->whereHas('applications', function ($q) use ($minScore, $maxScore) {
            $q->whereBetween('jamb_score', [$minScore, $maxScore]);
        });
    }
}