<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return view('admin.dashboard');
        } elseif ($user->isMentor()) {
            return view('mentor.dashboard');
        } else {
            return view('user.dashboard');
        }
    }
}