<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Built-in roles always get their dedicated dashboard (fast path)
        $builtInRedirects = [
            'Super Admin' => 'admin.dashboard',
            'Manager'     => 'admin.dashboard',
            'Doctor'      => 'doctor.dashboard',
            'Secretary'   => 'secretary.dashboard',
            'Cashier'     => 'cashier.seller-desk',
        ];

        foreach ($builtInRedirects as $roleName => $routeName) {
            if ($user->hasRole($roleName)) {
                return redirect()->intended(route($routeName));
            }
        }

        // Custom roles: use the dashboard_route configured on the role (if any)
        $customRole = $user->roles->whereNotNull('dashboard_route')->first();
        if ($customRole && Route::has($customRole->dashboard_route)) {
            return redirect()->intended(route($customRole->dashboard_route));
        }

        // Fallback for any role with no configured dashboard
        return redirect()->intended(route('user.profile'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
