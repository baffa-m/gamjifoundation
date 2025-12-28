<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Award extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'sponsor_id',
        'status',
        'required_documents',
        'slug',
        'amount',
        'number_of_awards',
        'category',
        'eligibility_criteria',
        'application_start_date',
        'application_end_date',
        'announcement_date',
        'status',
        'award_image'
    ];

    protected $casts = [
        'eligibility_criteria' => 'array',
        'application_start_date' => 'date',
        'application_end_date' => 'date',
        'announcement_date' => 'date',
        'amount' => 'decimal:2',
    ];


    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function isOpenForApplications()
    {
        $now = now();
        return $this->application_start_date <= $now 
            && $this->application_end_date >= $now 
            && $this->status === 'active';
    }
}