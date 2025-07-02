<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemadminController extends Controller
{
    public function pendingUsers()
    {
        // Example: Get users with 'pending' status
        $pendingUsers = \App\Models\User::where('status', 'pending')->get();
        return view('dashboard.pending-users', compact('pendingUsers'));
    }

    public function approve(Request $request)
    {
        // Example: Approve a user by ID
        $user = \App\Models\User::findOrFail($request->input('user_id'));
        $user->status = 'approved';
        $user->save();
        // Optionally, send notification here
        return redirect()->route('dashboard.pending-users')->with('success', 'User approved successfully.');
    }

    public function reject(Request $request)
    {
        // Example: Reject a user by ID
        $user = \App\Models\User::findOrFail($request->input('user_id'));
        $user->status = 'rejected';
        $user->save();
        // Optionally, send notification here
        return redirect()->route('dashboard.pending-users')->with('success', 'User rejected successfully.');
    }

    public function redirectToDashboard()
    {
        // Example: Redirect user to their dashboard based on category
        $user = \Illuminate\Support\Facades\Auth::user();
        switch ($user->category) {
            case 'systemadmin':
                return redirect()->route('dashboard.systemadmin');
            case 'staff':
                return redirect()->route('dashboard.staff');
            case 'customer':
                return redirect()->route('dashboard.customer');
            case 'supplier':
                return redirect()->route('dashboard.supplier');
            case 'wholesaler':
                return redirect()->route('dashboard.wholesaler');
            default:
                return redirect('/');
        }
    }
}