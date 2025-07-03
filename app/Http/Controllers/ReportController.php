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
        return redirect()->back()->withInput()->with('error', 'Failed to submit report. Please try again.');
    }
}

    public function index()
    {
        $reports = Report::with('photos')->latest()->paginate(10);
        return Inertia::render('Reports/Index', [
            'reports' => $reports,
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
