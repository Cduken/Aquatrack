<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationsController extends Controller
{
    public function getReports(Request $request)
    {
        try {
            $reports = Report::query()
                ->where('updated_at', '>', Carbon::now()->subDays(30))
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'tracking_code' => $report->tracking_code,
                        'status' => $report->status,
                        'updated_at' => $report->updated_at->toDateTimeString(),
                        'barangay' => $report->barangay,
                        'municipality' => $report->municipality,
                    ];
                });

            return response()->json([
                'success' => true,
                'reports' => $reports,
                'message' => 'Reports fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reports: ' . $e->getMessage()
            ], 500);
        }
    }
}
