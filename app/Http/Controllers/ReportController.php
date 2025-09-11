<?php

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

    public function destroy(Request $request, Report $report)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Update the report's status with the reason
        $report->status = "Deleted: " . $validated['reason'];
        $report->save();

        // Soft delete the report
        $report->delete();

        // Log the deletion activity
        Activity::create([
            'event' => 'report_deleted',
            'description' => "Report deleted with reason: {$validated['reason']}",
            'subject_type' => get_class($report),
            'subject_id' => $report->id,
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => [
                'tracking_code' => $report->tracking_code,
                'reason' => $validated['reason'],
            ],
        ]);

        // Return an Inertia redirect response instead of JSON
        return redirect()->route('admin.reports')->with([
            'swal' => [
                'icon' => 'success',
                'title' => 'Report Deleted',
                'text' => 'Report deleted successfully.',
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Reports/Index', [
            'zones' => $this->zones,
        ]);
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
                'photos' => 'required|array|min:1|max:5',
                'photos.*' => [
                    'file',
                    'mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm',
                    function ($attribute, $value, $fail) {
                        $originalName = $value->getClientOriginalName();
                        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                        $imageExtensions = ['jpeg', 'png', 'jpg', 'gif', 'webp'];
                        $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                        if (!in_array($extension, array_merge($imageExtensions, $videoExtensions))) {
                            $fail('The file must be an image (jpeg,png,jpg,gif,webp) or video (mp4,mov,avi,webm).');
                        }
                        if (in_array($extension, $imageExtensions) && $value->getSize() > 5 * 1024 * 1024) {
                            $fail('The photo must not exceed 5MB.');
                        }
                        if (in_array($extension, $videoExtensions) && $value->getSize() > 25 * 1024 * 1024) {
                            $fail('The video must not exceed 25MB.');
                        }
                    }
                ],
                'reporter_name' => 'required|string|max:255',
                'reporter_phone' => 'nullable|string|max:11',
                'priority' => 'required|in:low,medium,high',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

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
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
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
                    'status' => $report->status,
                ]
            ]);

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

            // Check if user is authenticated
            if (Auth::check()) {
                // Redirect to a success page or show a view
                return redirect()->route('customer.reports') // Redirect to customer reports page
                    ->with('swal', [
                        'icon' => 'success',
                        'title' => 'Report Submitted',
                        'text' => 'Your report has been submitted successfully!',
                        'trackingCode' => $trackingCode, // Include tracking code for the toast
                    ]);
            }

            // Return JSON response for unauthenticated or AJAX requests
            return response()->json([
                'success' => true,
                'message' => 'Report submitted successfully',
                'trackingCode' => $trackingCode,
                'dateSubmitted' => now()->toISOString(),
                'reportId' => $report->id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (Auth::check()) {
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            if (Auth::check()) {
                return redirect()->back()->with('error', 'Failed to submit report: ' . $e->getMessage());
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit report: ' . $e->getMessage()
            ], 500);
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
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Reports', [
            'reports' => $reports,
            'zones' => $this->zones,
            'swal' => $request->session()->get('swal')
        ]);
    }

    public function adminIndex(Request $request)
    {
        $query = Report::with(['photos', 'user'])
            ->whereNull('deleted_at')
            ->latest();

        if ($request->userType === 'guest') {
            $query->whereNull('user_id');
        } elseif ($request->userType === 'authenticated') {
            $query->whereNotNull('user_id');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(5)
            ->appends($request->query());

        return Inertia::render('Admin/Reports', [
            'reports' => $reports,
            'filters' => $request->only(['userType', 'status', 'search']),
            'canEdit' => true,
            'canDelete' => true,
            'swal' => $request->session()->get('swal')
        ]);
    }

    public function track(Request $request)
    {
        if ($request->isMethod('get')) {
            return inertia()->render('Reports/Track');
        }

        $request->validate([
            'tracking_code' => 'required|string|exists:reports,tracking_code,deleted_at,NULL'
        ]);

        $report = Report::with(['photos'])
            ->where('tracking_code', $request->tracking_code)
            ->whereNull('deleted_at')
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
            ->whereNull('deleted_at')
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

        return Inertia::render('Admin/Reports', [
            'swal' => [
                'icon' => 'success',
                'title' => 'Status Updated',
                'text' => 'Report status updated successfully!',
            ]
        ]);
    }
}
