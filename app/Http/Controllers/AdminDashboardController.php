<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // return admin dashboard view or data
        return view('dashboard.admin-dashboard');
    }
}

