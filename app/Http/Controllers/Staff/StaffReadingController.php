<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MeterReading;
use Illuminate\Support\Facades\Log;
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

            if (empty($query)) {
                return response()->json([]);
            }

            // Clean special characters from phone numbers
            $cleanPhone = preg_replace('/[^0-9]/', '', $query);

            $users = User::where('role', 'customer')
                ->where(function ($q) use ($query, $cleanPhone) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('account_number', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$cleanPhone}%");
                })
                ->select([
                    'id',
                    'name',
                    'account_number',
                    'zone',
                    'barangay',
                    'phone'
                ])
                ->limit(10)
                ->get();

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
            'account_number' => $user->account_number,
            'address' => $user->zone . ', ' . $user->barangay,
            'phone' => $user->phone,
            'date_installed' => $user->date_installed ?? 'Not available',
            'brand' => $user->brand ?? 'Not specified',
            'serial_number' => $user->serial_number ?? 'N/A',
            'size' => $user->size ?? 'N/A'
        ]);
    }

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
                        'billing_month' => $reading->billing_month,
                        'reading_date' => $reading->reading_date ? $reading->reading_date->format('Y-m-d') : 'N/A',
                        'reading' => $reading->reading,
                        'consumption' => $reading->consumption,
                        'amount' => $reading->amount
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

    public function storeReading(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'billing_month' => 'required|string',
            'reading_date' => 'required|date',
            'reading' => 'required|numeric|min:0',
        ]);

        $previousReading = MeterReading::where('user_id', $validated['user_id'])
            ->latest('reading_date')
            ->first();

        $consumption = $previousReading
            ? $validated['reading'] - $previousReading->reading
            : 0;

        $amount = $consumption * 15; // Adjust rate as needed

        $newReading = MeterReading::create([
            'user_id' => $validated['user_id'],
            'billing_month' => $validated['billing_month'],
            'reading_date' => $validated['reading_date'],
            'reading' => $validated['reading'],
            'consumption' => $consumption,
            'amount' => $amount
        ]);

        return response()->json([
            'message' => 'Reading saved successfully',
            'reading' => $newReading
        ]);
    }
}
