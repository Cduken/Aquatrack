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
        $validated = $request->validate([
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'purok' => 'required|string|max:255',
            'description' => 'required|string',
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            // Add these if you want to collect guest information
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
        ]);

        // Create report
        $reportData = [
            'municipality' => $validated['municipality'],
            'province' => $validated['province'],
            'barangay' => $validated['barangay'],
            'purok' => $validated['purok'],
            'description' => $validated['description'],
            'user_id' => Auth::id(), // Will be null for guests
        ];

        // Add guest information if available
        if (!Auth::check()) {
            $reportData['guest_name'] = $validated['guest_name'] ?? null;
            $reportData['guest_email'] = $validated['guest_email'] ?? null;
            $reportData['guest_phone'] = $validated['guest_phone'] ?? null;
        }

        $report = Report::create($reportData);

        // Store photos
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

        return redirect()->route('reports.index')->with('success', 'Report created successfully!');
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
        $report->load(['photos', 'user']); // Eager load relationships

        return Inertia::render('Reports/Show', [
            'report' => $report
        ]);
    }
}
