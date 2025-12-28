<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'applicant_id',
        'award_id',
        'application_number',
        'application_date',
        'application_status',
        'supporting_documents',
        'jamb_score',

    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function award(): BelongsTo
    {
        return $this->belongsTo(Award::class);
    }

    public function scopePending($query)
    {
        return $query->where('application_status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('application_status', 'under_review');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('application_status', 'shortlisted');
    }

    public function scopeApproved($query)
    {
        return $query->where('application_status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('application_status', 'rejected');
    }

    public function scopeByApplicant($query, $applicantId)
    {
        return $query->where('applicant_id', $applicantId);
    }
}