<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /* public function toDashboard(){
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $dashboardUrl = $user->redirectToDashboard();
        return redirect($dashboardUrl);
    } */
}
