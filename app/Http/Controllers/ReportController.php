<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Log;
//   use Illuminate\Support\Facades\Facade;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        try {
        $validated = $request->validate([
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'purok' => 'required|string|max:255',
            'problem_type' => 'required|string|max:255', // ✅ renamed from 'type'
            'description' => 'required|string',
            'photos' => 'required|array|min:1',
            'photos.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:20480',
            'reporter_name' => 'required|string|max:255',
            'reporter_phone' => 'nullable|string|max:11',
        ]);

        $reportData = [
            'municipality' => $validated['municipality'],
            'province' => $validated['province'],
            'barangay' => $validated['barangay'],
            'purok' => $validated['purok'],
            'problem_type' => $validated['problem_type'],
            'description' => $validated['description'],
            'reporter_name' => $validated['reporter_name'],
            'reporter_phone' => $validated['reporter_phone'] ?? null,
            'user_id' => Auth::id(),
        ];

            if (!Auth::check()) {
                $reportData['reporter_name'] = $validated['reporter_name'] ?? null;
                $reportData['reporter_phone'] = $validated['reporter_phone'] ?? null;
            }

            $report = Report::create($reportData);

            foreach ($request->file('photos') as $photo) {
                $originalName = $photo->getClientOriginalName();
                $extension = $photo->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = $photo->storeAs('report-photos', $filename, 'public');

                ReportPhoto::create([
                    'report_id' => $report->id,
                    'path' => $path,
                    'original_name' => $originalName,
                    'mime_type' => $photo->getClientMimeType(),
                    'size' => $photo->getSize()
                ]);
            }

            return redirect()->route('reports.index')->with('success', 'Report submitted successfully!');
        } catch (\Exception $e) {
            // \Log::error('Report submission failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to submit report. Please try again.');
        }
    }

    public function index()
    {
        $reports = Report::with('photos')
            ->where('user_id', Auth::id()) // 🔐 Only show reports by the logged-in user
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Reports', [ // ✅ Updated path
            'reports' => $reports,
        ]);
    }
    public function adminIndex()
{
    $reports = Report::with(['user', 'photos']) // eager load relationships
        ->latest()
        ->paginate(10);

    return Inertia::render('Admin/Reports', [
        'reports' => $reports
    ]);
}
public function updateAdmin(Request $request, Report $report)
{
    $validated = $request->validate([
        'priority' => 'required|string|in:Low,Medium,High,Low-Medium',
        'status' => 'required|string|in:Pending,In Progress,Resolved',
    ]);

    $report->update($validated);

    return back()->with('success', 'Report updated successfully.');
}
public function destroy(Report $report)
{
    $report->delete();
    return back()->with('success', 'Report deleted successfully.');
}




    public function show(Report $report)
    {
        $report->load(['photos', 'user']);

        return Inertia::render('Reports/Show', [
            'report' => $report
        ]);
    }
}
