<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportPhoto;
use Carbon\Carbon;
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

            // Generate tracking code (AQT-yyyymmdd-4random)
            $datePart = now()->format('Ymd');
            $randomPart = substr(md5(uniqid()), 0, 4);
            $trackingCode = 'AQT-' . $datePart . '-' . strtoupper($randomPart);

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
                'tracking_code' => $trackingCode,
            ];

            $report = Report::create($reportData);

            // Handle photo uploads
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

            // Redirect to success page with tracking information
            return redirect()->route('reports.success')->with([
                'trackingCode' => $trackingCode,
                'dateSubmitted' => Carbon::now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to submit report. Please try again.');
        }
    }

    public function success(Request $request)
    {
        // Check if we have the tracking data in session
        if (!$request->session()->has('trackingCode')) {
            return redirect()->route('home');
        }

        return Inertia::render('Reports/Success', [
            'trackingCode' => $request->session()->get('trackingCode'),
            'dateSubmitted' => $request->session()->get('dateSubmitted'),
        ]);
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

    // In your ReportController.php
    public function track(Request $request)
    {
        // If it's a GET request, just show the tracking form
        if ($request->isMethod('get')) {
            return inertia()->render('Reports/Track');
        }

        // Handle POST request for tracking submission
        $request->validate([
            'tracking_code' => 'required|string|exists:reports,tracking_code'
        ]);

        $report = Report::with(['photos'])
            ->where('tracking_code', $request->tracking_code)
            ->firstOrFail();

        return inertia()->render('Reports/Track', [
            'report' => $report
        ]);
    }

    public function findByTrackingCode(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string'
        ]);

        $report = Report::with(['photos'])
            ->where('tracking_code', $request->tracking_code)
            ->first();

        if (!$report) {
            return response()->json([
                'message' => 'No report found with this tracking code.'
            ], 404);
        }

        return response()->json($report);
    }
}
