<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Only accessible by admin
     */
    public function create(): View|RedirectResponse
    {
        // Only admin can access staff registration
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Only admin can register new staff accounts.');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Creates staff accounts only (for admin use)
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff', // ✅ Always staff when registered via web
        ]);

        event(new Registered($user));

        // Don't auto-login — redirect back to dashboard with success
        return redirect()->route('admin.dashboard')
            ->with('success', 'Staff account created successfully for ' . $user->name . '!');
    }
}