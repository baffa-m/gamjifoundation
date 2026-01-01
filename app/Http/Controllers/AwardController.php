<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awards = Award::with('sponsor.user')
            ->whereDate('application_end_date', '>=', now())
            ->latest()
            ->get()
            ->map(function ($award) {
                return [
                    'id' => $award->id,
                    'title' => $award->title,
                    'slug' => $award->slug ?? $award->id,
                    'type' => $award->type,
                    'amount' => $award->amount, // You might want to format this
                    'sponsor' => $award->sponsor->user->name ?? 'Gamji Foundation',
                    'deadline' => $award->application_end_date->format('M d, Y'),
                    'is_active' => $award->isActive(),
                ];
            });

        return Inertia::render('Awards/Index', [
            'awards' => $awards
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Try to find by ID first, then slug
        $award = Award::with(['sponsor.user'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        // Transform for the frontend
        $awardData = [
            'id' => $award->id,
            'title' => $award->title,
            'slug' => $award->slug ?? $award->id,
            'description' => $award->description,
            'amount' => $award->amount,
            'type' => $award->type,
            'category' => $award->category,
            'number_of_awards' => $award->number_of_awards,
            'application_start_date' => $award->application_start_date,
            'application_end_date' => $award->application_end_date,
            'announcement_date' => $award->announcement_date,
            'sponsor' => $award->sponsor,
            'eligibility_criteria' => $award->eligibility_criteria,
            'required_documents' => $award->required_documents,
        ];

        return Inertia::render('Awards/Show', [
            'award' => $awardData
        ]);
    }
}
