<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Verify admin/staff access code.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,staff',
            'code' => 'required|string'
        ]);

        $role = strtoupper($request->role);
        $correctCode = env("{$role}_VERIFICATION_CODE");

        if (!$correctCode || $request->code !== $correctCode) {
            throw ValidationException::withMessages([
                'code' => ['The provided verification code is invalid'],
            ]);
        }

        return response()->json([
            'verified' => true,
            'message' => 'Verification successful'
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = Auth::user();
        $selectedRole = strtolower($request->input('role', 'customer'));

        // Prevent admin/staff from logging in through customer form
        if ($selectedRole === 'customer' && $user->hasAnyRole(['admin', 'staff'])) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Administrative accounts must use their specific login portal'],
            ]);
        }

        // Update the role verification section in the store() method
        if (in_array($selectedRole, ['admin', 'staff']) && !$user->hasRole($selectedRole)) {
            Auth::logout();

            $userRoles = $user->roles->pluck('name')->implode(', ');

            throw ValidationException::withMessages([
                'role_mismatch' => [
                    'title' => 'Role Access Denied',
                    'message' => "Your account has these roles: $userRoles. " .
                        "Please login through the appropriate portal for your account type."
                ]
            ]);
        }

        $request->session()->regenerate();

        // Improved redirect logic without nested ternaries
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->hasRole('staff')) {
            return redirect()->intended(route('staff.dashboard'));
        }

        return redirect()->intended(route('customer.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return a proper response that will trigger page reload
        return Inertia::location(route('select-roles'));
    }
}
