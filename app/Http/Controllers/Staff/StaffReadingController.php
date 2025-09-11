<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MeterReading;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StaffReadingController extends Controller
{
    public function index()
    {
        return Inertia::render('Staff/Reading');
    }

    public function search(Request $request)
    {
        try {
            $query = trim($request->input('query', ''));

            if (empty($query) || strlen($query) < 2) {
                return response()->json([]);
            }

            $users = User::where('role', 'customer')
                ->where(function ($q) use ($query) {
                    // Search by name (first or last name)
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('lastname', 'like', "%{$query}%");

                    // Search by serial number
                    $q->orWhere('serial_number', 'like', "%{$query}%");
                })
                ->select([
                    'id',
                    'name',
                    'lastname',
                    'account_number',
                    'zone',
                    'barangay',
                    'municipality',
                    'province',
                    'phone',
                    'date_installed',
                    'brand',
                    'serial_number',
                    'size',
                    'avatar',
                ])
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'lastname' => $user->lastname,
                        'account_number' => $user->account_number,
                        'address' => implode(', ', array_filter([
                            $user->zone,
                            $user->barangay,
                            $user->municipality,
                            $user->province
                        ])),
                        'phone' => $user->phone,
                        'date_installed' => $user->date_installed,
                        'brand' => $user->brand,
                        'serial_number' => $user->serial_number,
                        'size' => $user->size,
                        'avatar_url' => $user->avatar ? Storage::url($user->avatar) : null
                    ];
                });

            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function getUserDetails($userId)
    {
        $user = User::where('id', $userId)
            ->where('role', 'customer')
            ->firstOrFail();

        return response()->json([
            'name' => $user->name,
            'lastname' => $user->lastname,
            'account_number' => $user->account_number,
            'address' => implode(', ', array_filter([
                $user->zone,
                $user->barangay,
                $user->municipality,
                $user->province
            ])),
            'phone' => $user->phone,
            'date_installed' => $user->date_installed ?? 'Not available',
            'brand' => $user->brand ?? 'Not specified',
            'serial_number' => $user->serial_number ?? 'N/A',
            'size' => $user->size ?? 'N/A'
        ]);
    }

    // In StaffReadingController.php
    public function getPreviousReadings($userId)
    {
        try {
            // Validate the user exists and is a customer
            $user = User::where('id', $userId)
                ->where('role', 'customer')
                ->firstOrFail();

            $readings = MeterReading::where('user_id', $userId)
                ->orderBy('reading_date', 'desc')
                ->limit(12)
                ->get()
                ->map(function ($reading) {
                    return [
                        'id' => $reading->id,
                        'billing_month' => $reading->billing_month,
                        'reading_date' => $reading->reading_date ? $reading->reading_date->format('Y-m-d') : 'N/A',
                        'reading' => $reading->reading,
                        'previous_reading' => $reading->previous_reading,
                        'consumption' => $reading->consumption,
                        'amount' => $reading->amount,
                        'status' => $reading->status,
                        'year' => $reading->reading_date ? $reading->reading_date->format('Y') : date('Y')
                    ];
                });

            return response()->json($readings);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching previous readings: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }


    // StaffReadingController.php
    public function storeReading(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'billing_month' => 'required|string',
            'reading_date' => 'required|date',
            'reading' => 'required|numeric|min:0',
            'previous_reading' => 'nullable|numeric|min:0',
        ]);

        // Parse the reading date to get year
        $readingDate = new \DateTime($validated['reading_date']);
        $readingYear = $readingDate->format('Y');

        // Check if reading already exists for this month and year
        $existingReading = MeterReading::where('user_id', $validated['user_id'])
            ->where('billing_month', $validated['billing_month'])
            ->whereYear('reading_date', $readingYear)
            ->first();

        if ($existingReading) {
            return response()->json([
                'error' => 'A reading already exists for this billing month and year'
            ], 422);
        }

        // Determine the correct previous reading
        $previousReadingValue = $this->determinePreviousReading(
            $validated['user_id'],
            $validated['previous_reading'] ?? null,
            $readingDate
        );

        // Validate that current reading is greater than previous reading
        if ($validated['reading'] <= $previousReadingValue) {
            return response()->json([
                'error' => 'Current reading must be greater than previous reading'
            ], 422);
        }

        $consumption = $validated['reading'] - $previousReadingValue;
        $amount = $this->calculateBillAmount($consumption);

        $newReading = MeterReading::create([
            'user_id' => $validated['user_id'],
            'staff_id' => Auth::id(),
            'billing_month' => $validated['billing_month'],
            'reading_date' => $validated['reading_date'],
            'previous_reading' => $previousReadingValue, // Store the previous reading
            'reading' => $validated['reading'],
            'consumption' => $consumption,
            'amount' => $amount,
            'status' => 'Pending'
        ]);

        return response()->json([
            'message' => 'Reading saved successfully',
            'reading' => $newReading
        ]);
    }

    /**
     * Determine the correct previous reading value
     */
    private function determinePreviousReading($userId, $providedPreviousReading, $currentReadingDate)
    {
        // If previous reading is provided, use it
        if ($providedPreviousReading !== null) {
            return (float) $providedPreviousReading;
        }

        // Get the most recent reading before the current reading date
        $previousReading = MeterReading::where('user_id', $userId)
            ->where('reading_date', '<', $currentReadingDate->format('Y-m-d'))
            ->orderBy('reading_date', 'desc')
            ->first();

        if ($previousReading) {
            return $previousReading->reading;
        }

        // If no previous reading found, this might be the first reading
        return 0;
    }

    /**
     * Calculate bill amount based on tiered pricing
     * 1-10 m³ = ₱132 (fixed)
     * 11-20 m³ = ₱14 per m³
     * 21-30 m³ = ₱14.85 per m³
     * 31-40 m³ = ₱16 per m³
     * 41+ m³ = ₱17.25 per m³
     */
    private function calculateBillAmount($consumption)
    {
        if ($consumption <= 0) {
            return 0;
        }

        if ($consumption <= 10) {
            return 132.00; // Fixed rate for first 10 m³
        }

        $amount = 132.00; // Base amount for first 10 m³

        if ($consumption > 10 && $consumption <= 20) {
            $amount += ($consumption - 10) * 14;
        } elseif ($consumption > 20 && $consumption <= 30) {
            $amount += (10 * 14) + (($consumption - 20) * 14.85);
        } elseif ($consumption > 30 && $consumption <= 40) {
            $amount += (10 * 14) + (10 * 14.85) + (($consumption - 30) * 16);
        } else {
            $amount += (10 * 14) + (10 * 14.85) + (10 * 16) + (($consumption - 40) * 17.25);
        }

        return round($amount, 2);
    }
}
