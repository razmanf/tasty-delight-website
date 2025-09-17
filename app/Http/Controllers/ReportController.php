<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show reports dashboard wrapper view (contains Livewire component).
     */
    public function index()
    {
        return view('admin.reports.index');
    }
}
