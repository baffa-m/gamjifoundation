<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with(['user', 'applications'])->latest();

        if ($request->search) {
            $query->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        if ($request->state) {
            $query->where('state_of_origin', $request->state);
        }

        return Inertia::render('Admin/Applicants/Index', [
            'applicants' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['search', 'state'])
        ]);
    }

    public function show(Applicant $applicant)
    {
        return Inertia::render('Admin/Applicants/Show', [
            'applicant' => $applicant->load(['user', 'applications.award'])
        ]);
    }

    public function destroy(Applicant $applicant)
    {
        if ($applicant->applications()->exists()) {
            return back()->with('error', 'Cannot delete applicant with existing applications.');
        }

        $applicant->delete();

        return redirect()->route('admin.applicants.index')
            ->with('success', 'Applicant deleted successfully.');
    }
}
