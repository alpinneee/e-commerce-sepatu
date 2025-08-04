<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Security stats
        $totalAdmins = User::role('admin')->count();
        $recentLogins = User::role('admin')
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();
        
        // Recent admin activities (mock data)
        $recentActivities = collect([
            [
                'user' => $user->name,
                'action' => 'Login',
                'ip' => request()->ip(),
                'time' => now()->subMinutes(5),
                'status' => 'success'
            ],
            [
                'user' => $user->name,
                'action' => 'Updated Product',
                'ip' => request()->ip(),
                'time' => now()->subHours(2),
                'status' => 'success'
            ]
        ]);

        return view('admin.security.index', compact('user', 'totalAdmins', 'recentLogins', 'recentActivities'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully');
    }
}