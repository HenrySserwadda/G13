<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemadminController extends Controller
{
    /**
     * Show the form for making a user a system administrator.
     */
    public function makeSystemAdministrator()
    {
        $users = \App\Models\User::all();
        return view('dashboard.systemadmin.make-system-administrator', compact('users'));
    }
    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('dashboard.systemadmin.all-users')->with('success', 'User deleted successfully.');
    }
    /**
     * Display a listing of all users for the system admin dashboard.
     */
    public function allUsers()
    {
        $users = \App\Models\User::all();
        return view('dashboard.systemadmin.all-users', compact('users'));
    }
    public function pendingUsers()
    {
        // Example: Get users with 'pending' status
        $pendingUsers = \App\Models\User::where('status', 'pending')->get();
        return view('dashboard.systemadmin.pending-users', ['users' => $pendingUsers]);
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