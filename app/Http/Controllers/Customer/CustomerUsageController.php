<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MeterReading;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CustomerUsageController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch meter readings for the authenticated user
        $readings = MeterReading::where('user_id', $user->id)
            ->orderBy('reading_date', 'desc')
            ->limit(12)
            ->get()
            ->map(function ($reading) {
                return [
                    'id' => $reading->id, // Add ID for updates
                    'month' => $reading->billing_month . ' ' . $reading->reading_date->format('Y'),
                    'usage' => $reading->consumption,
                    'amount' => number_format($reading->amount, 2),
                    'status' => $reading->status ?? ($reading->amount > 0 ? 'Pending' : 'Paid'), // Use actual status if available
                ];
            });

        // Prepare chart data
        $chartData = MeterReading::where('user_id', $user->id)
            ->orderBy('reading_date', 'asc')
            ->limit(6)
            ->get()
            ->map(function ($reading) {
                return [
                    'month' => $reading->billing_month,
                    'usage' => $reading->consumption,
                    'amount' => $reading->amount,
                ];
            });

        return Inertia::render('Customer/Usage', [
            'usageData' => $readings,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Update the status of a meter reading (mark as paid)
     */
    public function update(Request $request, $id)
    {
        // Find the meter reading
        $reading = MeterReading::findOrFail($id);

        // Authorization check - only admins can update status
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:Pending,Paid'
        ]);

        // Update the status
        $reading->status = $validated['status'];
        $reading->save();

        return response()->json([
            'message' => 'Bill status updated successfully!',
            'reading' => [
                'id' => $reading->id,
                'month' => $reading->billing_month . ' ' . $reading->reading_date->format('Y'),
                'usage' => $reading->consumption,
                'amount' => number_format($reading->amount, 2),
                'status' => $reading->status,
            ]
        ]);
    }
}
