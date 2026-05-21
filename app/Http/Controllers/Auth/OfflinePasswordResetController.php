<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfflinePasswordResetController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function findAccount(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;

        // Email must belong to a real account
        if (!User::where('email', $email)->exists()) {
            return back()
                ->withErrors(['email' => 'No account found with that email address.'])
                ->withInput();
        }

        $existing = PasswordResetRequest::latestFor($email);

        if ($existing && $existing->isApproved()) {
            // Admin already approved — let them set a new password
            session(['offline_reset_email' => $email]);
            return redirect()->route('password.offline.form');
        }

        if ($existing && $existing->isPending()) {
            return back()
                ->with('request_status', 'pending')
                ->withInput();
        }

        // Create a fresh pending request
        // Close any old rejected/completed requests first
        PasswordResetRequest::where('email', $email)
            ->whereIn('status', ['rejected', 'completed'])
            ->update(['status' => 'completed']);

        PasswordResetRequest::create(['email' => $email, 'status' => 'pending']);

        return back()
            ->with('request_status', 'submitted')
            ->withInput();
    }

    public function showResetForm()
    {
        $email = session('offline_reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please enter your email address first.']);
        }

        // Re-verify approval is still valid before showing the form
        $req = PasswordResetRequest::latestFor($email);
        if (!$req || !$req->isApproved()) {
            session()->forget('offline_reset_email');
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your reset request is no longer approved. Please submit a new request.']);
        }

        return view('auth.offline-reset-password', ['email' => $email]);
    }

    public function reset(Request $request)
    {
        $email = session('offline_reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please start over.']);
        }

        $req = PasswordResetRequest::latestFor($email);
        if (!$req || !$req->isApproved()) {
            session()->forget('offline_reset_email');
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your reset approval has been revoked. Contact the administrator.']);
        }

        $request->validate([
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        User::where('email', $email)->firstOrFail()->update([
            'password' => Hash::make($request->password),
        ]);

        // Mark request completed so it can't be reused
        $req->update(['status' => 'completed', 'actioned_at' => now()]);
        session()->forget('offline_reset_email');

        return redirect()->route('login')
            ->with('status', 'Password reset successfully. You may now log in.');
    }
}
