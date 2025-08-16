<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Activity;
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

    protected $zones = [
        'Zone 1' => ['Poblacion Sur'],
        'Zone 2' => ['Poblacion Centro'],
        'Zone 3' => ['Poblacion Centro'],
        'Zone 4' => ['Poblacion Norte'],
        'Zone 5' => ['Candajec', 'Buangan'],
        'Zone 6' => ['Bonbon'],
        'Zone 7' => ['Bonbon'],
        'Zone 8' => ['Nahawan'],
        'Zone 9' => ['Caboy', 'Villaflor', 'Cantuyoc'],
        'Zone 10' => ['Bacani', 'Mataub', 'Comaang', 'Tangaran'],
        'Zone 11' => ['Cantuyoc', 'Nahawan'],
        'Zone 12' => ['Lajog', 'Buacao'],
    ];

    public function create()
    {
        return Inertia::render('Reports/Index');
    }
    public function store(Request $request)
    {


        try {
            $validated = $request->validate([
                'municipality' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'zone' => 'required|string',
                'barangay' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        $zone = $request->input('zone');
                        if (!in_array($value, $this->zones[$zone] ?? [])) {
                            $fail('The selected barangay is not valid for the chosen zone.');
                        }
                    },
                ],
                'purok' => 'required|string|max:255',
                'description' => 'required|string',
                'photos' => 'required|array|min:1',
                'photos.*' => [
                    'file',
                    'mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi',
                    'max:15360', // 15MB
                    function ($attribute, $value, $fail) {
                        // Get the original file name to check extension
                        $originalName = $value->getClientOriginalName();
                        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                        $imageExtensions = ['jpeg', 'png', 'jpg', 'gif', 'webp'];
                        $videoExtensions = ['mp4', 'mov', 'avi'];

                        if (!in_array($extension, array_merge($imageExtensions, $videoExtensions))) {
                            $fail('The file must be an image (jpeg,png,jpg,gif,webp) or video (mp4,mov,avi).');
                        }
                    }
                ],
                'reporter_name' => 'required|string|max:255',
                'reporter_phone' => 'nullable|string|max:11',
                'priority' => 'required|in:low,medium,high',

            ]);

            // Generate tracking code (AQT-yyyymmdd-4random)
            $datePart = now()->format('Ymd');
            $randomPart = substr(md5(uniqid()), 0, 4);
            $trackingCode = 'AQT' . $datePart . '-' . strtoupper($randomPart);

            $reportData = [
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
                'barangay' => $validated['barangay'],
                'purok' => $validated['purok'],
                'zone' => $validated['zone'],
                'description' => $validated['description'],
                'reporter_name' => $validated['reporter_name'],
                'reporter_phone' => $validated['reporter_phone'] ?? null,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'tracking_code' => $trackingCode,
                'priority' => $validated['priority'],
            ];

            $report = Report::create($reportData);

            Activity::create([
                'event' => 'report_created',
                'description' => 'New report submitted: ' . $report->tracking_code,
                'subject_type' => get_class($report),
                'subject_id' => $report->id,
                'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
                'causer_id' => Auth::id(),
                'properties' => [
                    'tracking_code' => $report->tracking_code,
                    'zone' => $report->zone,
                    'status' => $report->status
                ]
            ]);

            // Handle photo uploads
            foreach ($request->file('photos') as $photo) {
                $originalName = $photo->getClientOriginalName();
                $extension = $photo->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = $photo->storeAs('report-photos', $filename, 'public');

                $type = in_array(strtolower($extension), ['mp4', 'mov', 'avi']) ? 'video' : 'photo';

                ReportPhoto::create([
                    'report_id' => $report->id,
                    'path' => $path,
                    'original_name' => $originalName,
                    'mime_type' => $photo->getClientMimeType(),
                    'size' => $photo->getSize()
                ]);
            }

            // Check if user is authenticated
            if (Auth::check()) {
                // For authenticated users, redirect back to reports list with success message
                return redirect()->route('customer.reports')->with([
                    'success' => 'Report submitted successfully!',
                    'trackingCode' => $trackingCode
                ]);
            } else {
                // For guests, redirect to success page
                return redirect()->route('reports.success')->with([
                    'trackingCode' => $trackingCode,
                    'dateSubmitted' => Carbon::now()->toDateTimeString(),
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to submit report. Please try again.');
        }
    }

    public function success(Request $request)
    {

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
            'reports' => $reports,
            'flash' => [
                'success' => $request->session()->get('success'),
                'trackingCode' => $request->session()->get('trackingCode')
            ]
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
            'reports' => $query->paginate(10)
                ->appends($request->query()),
            'filters' => $request->only(['userType', 'status', 'search']),
            'canEdit' => true // Add this line to always enable editing for admin
        ]);
    }

    public function publicIndex(Request $request)
    {
        $reports = Report::with(['photos', 'user'])
            ->latest()
            ->paginate(10);

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

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved'
        ]);

        $oldStatus = $report->status;
        $report->status = $request->status;
        $report->save();

        // Log the status change activity
        Activity::create([
            'event' => 'report_status_changed',
            'description' => "Report status changed from {$oldStatus} to {$report->status}",
            'subject_type' => get_class($report),
            'subject_id' => $report->id,
            'causer_type' => get_class(Auth::user()),
            'causer_id' => Auth::id(),
            'properties' => [
                'tracking_code' => $report->tracking_code,
                'old_status' => $oldStatus,
                'new_status' => $report->status
            ]
        ]);

        return redirect()->route('admin.reports')->with('success', 'Report status updated successfully!');
    }
}
