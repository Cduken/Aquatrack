<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Activity;
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
            // Simplified logging without recursion
            Activity::create([
                'event' => 'verification_failed',
                'description' => "Failed {$request->role} verification code attempt",
                'properties' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            ]);

            throw ValidationException::withMessages([
                'code' => ['The provided verification code is invalid'],
            ]);
        }

        // Simplified success logging
        Activity::create([
            'event' => 'verification_success',
            'description' => "Successful {$request->role} verification",
            'properties' => [
                'ip' => $request->ip()
            ]
        ]);

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
        try {
            $request->authenticate();

            $user = Auth::user();
            $selectedRole = strtolower($request->input('role', 'customer'));

            // Prevent admin/staff from logging in through customer form
            if ($selectedRole === 'customer' && $user->hasAnyRole(['admin', 'staff'])) {
                Activity::log('unauthorized_access', "Admin/staff attempted customer login", $user, [
                    'ip' => $request->ip(),
                    'attempted_role' => $selectedRole
                ]);

                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Administrative accounts must use their specific login portal'],
                ]);
            }

            // Check role mismatch
            if (in_array($selectedRole, ['admin', 'staff']) && !$user->hasRole($selectedRole)) {
                $userRoles = $user->roles->pluck('name')->implode(', ');

                Activity::log('role_mismatch', "User attempted login with wrong role", $user, [
                    'attempted_role' => $selectedRole,
                    'actual_roles' => $userRoles
                ]);

                Auth::logout();
                throw ValidationException::withMessages([
                    'role_mismatch' => [
                        'title' => 'Role Access Denied',
                        'message' => "Your account has these roles: $userRoles. " .
                            "Please login through the appropriate portal for your account type."
                    ]
                ]);
            }

            $request->session()->regenerate();

            // Log successful login
            Activity::log('logged_in', "User logged in", $user, [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'role' => $selectedRole
            ]);

            // Improved redirect logic
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->hasRole('staff')) {
                return redirect()->intended(route('staff.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'));
        } catch (ValidationException $e) {
            // Log failed login attempt
            Activity::log('login_failed', "Failed login attempt", null, [
                'email' => $request->email,
                'ip' => $request->ip(),
                'errors' => $e->errors()
            ]);

            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Log logout activity
            Activity::log('logged_out', "User logged out", $user);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Add a flash message for logout success
        $request->session()->flash('logout_success', true);

        // Return a proper response that will trigger page reload
        return Inertia::location(route('select-roles'));
    }
}
