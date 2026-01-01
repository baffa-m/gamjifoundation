<?php

namespace App\Http\Controllers;

use App\Models\{HeroSlide, Award, News};
use Carbon\Carbon;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'slides' => HeroSlide::active()->get()->map(function ($slide) {
                return [
                    'image' => asset('storage/' . $slide->image),
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'cta' => $slide->cta_text,
                    'ctaLink' => $slide->cta_link
                ];
            }),
            'stats' => [
                ['value' => '500+', 'label' => 'Awards Given'],
                ['value' => Award::count() . 'K', 'label' => 'Active Awards'],
                ['value' => '₦45M', 'label' => 'Funds Disbursed'],
            ],
            'features' => [
                [
                    'title' => 'Scholarships',
                    'desc' => 'Full financial support for JAMB, WAEC, and University tuition.',
                    'icon' => 'GraduationCap'
                ],
                [
                    'title' => 'Live Tracking',
                    'desc' => 'Monitor your application status in real-time with our dashboard.',
                    'icon' => 'ClipboardList'
                ],
                [
                    'title' => 'E-Learning',
                    'desc' => 'Access curated study materials and past questions for success.',
                    'icon' => 'BookOpen'
                ],
                [
                    'title' => 'Mentorship',
                    'desc' => 'Connect with industry professionals and academic advisors.',
                    'icon' => 'Users'
                ]
            ],
            'awards' => Award::active()
                ->where('application_end_date', '>=', now())
                ->take(3)
                ->get()
                ->map(function($award) {
                    return [
                        'id' => $award->id,
                        'slug' => $award->slug,
                        'title' => $award->title,
                        'sponsor' => $award->sponsor->organization_name ?? 'N/A',
                        'amount' => $award->amount,
                        'deadline' => optional($award->application_end_date)->format('M d'),
                        'type' => ucfirst($award->category)
                    ];
                }),
            'latestNews' => News::published()
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($news) {
                    return [
                        'id' => $news->id,
                        'slug' => $news->slug,
                        'title' => $news->title,
                        'excerpt' => \Illuminate\Support\Str::limit($news->description ?? $news->content, 100),
                        'image' => $news->featured_image ? asset('storage/' . $news->featured_image) : null,
                        'category' => ucfirst($news->category),
                        'date' => optional($news->published_at)->format('M d, Y') ?? $news->created_at->format('M d, Y'),
                    ];
                })
        ]);
    }
}