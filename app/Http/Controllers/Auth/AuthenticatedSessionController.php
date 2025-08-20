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
use Illuminate\CsrfToken;

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
            'csrf_token' => csrf_token(), // Include CSRF token explicitly
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
        try {
            $request->validate([
                'role' => 'required|in:admin,staff',
                'code' => 'required|string'
            ]);

            $role = strtoupper($request->role);
            $correctCode = env("{$role}_VERIFICATION_CODE");

            if (!$correctCode || $request->code !== $correctCode) {
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

            Activity::create([
                'event' => 'verification_success',
                'description' => "Successful {$request->role} verification",
                'properties' => [
                    'ip' => $request->ip()
                ]
            ]);

            return response()->json([
                'verified' => true,
                'message' => 'Verification successful',
                'csrf_token' => csrf_token(), // Return new CSRF token
            ]);
        } catch (\Exception $e) {
            // Handle potential CSRF mismatch
            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                return response()->json([
                    'verified' => false,
                    'message' => 'CSRF token mismatch. Please refresh the page and try again.',
                    'csrf_token' => csrf_token(),
                ], 419);
            }
            throw $e;
        }
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
                        'message' => "Your account has these roles: $userRoles. Please login through the appropriate portal for your account type."
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

            // Role-based redirects
            $redirectRoute = match (true) {
                $user->hasRole('admin') => 'admin.dashboard',
                $user->hasRole('staff') => 'staff.dashboard',
                default => 'customer.dashboard',
            };

            return redirect()->intended(route($redirectRoute));
        } catch (ValidationException $e) {
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
            Activity::log('logged_out', "User logged out", $user);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Flash logout success and new CSRF token
        $request->session()->flash('logout_success', true);

        // Use Inertia::location to force a full page reload
        return Inertia::location(route('select-roles', [], false));
    }
}
