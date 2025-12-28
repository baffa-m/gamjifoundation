<?php 

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\{Applicant, Application, Award};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        if (!$applicant) {
            // Redirect to profile completion
            return redirect()->route('applicant.profile.create')
                ->with('message', 'Please complete your profile to continue.');
        }

        return Inertia::render('Applicant/Dashboard', [
            'stats' => [
                'total_applications' => Application::where('applicant_id', $applicant->id)->count(),
                'pending' => Application::where('applicant_id', $applicant->id)->pending()->count(),
                'approved' => Application::where('applicant_id', $applicant->id)->approved()->count(),
                'rejected' => Application::where('applicant_id', $applicant->id)->rejected()->count(),
                'available_awards' => Award::active()
                    ->where('application_end_date', '>=', now())
                    ->count(),
            ],
            'recent_applications' => Application::where('applicant_id', $applicant->id)
                ->with('award')
                ->latest()
                ->take(5)
                ->get(),
            'available_awards' => Award::active()
                ->where('application_end_date', '>=', now())
                ->take(3)
                ->get()
        ]);
    }
}