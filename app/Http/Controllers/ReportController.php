<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
                'description' => 'required|string',
                'photos' => 'required|array|min:1',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                'reporter_name' => 'required|string|max:255',
                'reporter_phone' => 'nullable|string|max:11',
            ]);

            $reportData = [
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
                'barangay' => $validated['barangay'],
                'purok' => $validated['purok'],
                'description' => $validated['description'],
                'reporter_name' => $validated['reporter_name'],
                'reporter_phone' => $validated['reporter_phone'] ?? null,
                'user_id' => Auth::id(),
                'status' => 'pending',
            ];

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

            $user = Auth::user();
            if ($user) {
                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.reports')->with('success', 'Report submitted successfully!');
                } elseif ($user->hasRole('customer')) {
                    return redirect()->route('customer.reports')->with('success', 'Report submitted successfully!');
                }
            }
            return redirect()->route('reports.index')->with('success', 'Report submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to submit report. Please try again.');
        }
    }

    public function customerIndex(Request $request)
    {
        $reports = Report::with(['photos'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Reports', [
            'reports' => $reports
        ]);
    }

    // In ReportController
    public function adminIndex(Request $request)
    {
        $query = Report::with(['photos', 'user'])
            ->latest();

        // Admin-specific filters
        if ($request->userType === 'guest') {
            $query->whereNull('user_id');
        } elseif ($request->userType === 'authenticated') {
            $query->whereNotNull('user_id');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(10)
            ->appends($request->query());

        return Inertia::render('Admin/Reports', [
            'reports' => $reports,
            'filters' => $request->only(['userType', 'status', 'search'])
        ]);
    }

    public function publicIndex(Request $request)
    {
        $reports = Report::with(['photos', 'user'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Reports/Index', [
            'reports' => $reports
        ]);
    }

    public function show(Report $report)
    {
        $report->load(['photos', 'user']);

        return Inertia::render('Reports/Show', [
            'report' => $report
        ]);
    }
}
