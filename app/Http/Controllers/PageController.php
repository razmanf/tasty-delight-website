<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function employees()
    {
        return view('employees');
    }

    public function appointments()
    {
        return view('appointments');
    }

    public function privacy()
    {
        $policy = File::exists(storage_path('app/policy.html'))
            ? File::get(storage_path('app/policy.html'))
            : 'Privacy Policy not found.';

        return view('policy', compact('policy'));
    }

    public function terms()
    {
        $terms = File::exists(storage_path('app/terms.html'))
            ? File::get(storage_path('app/terms.html'))
            : 'Terms of Service not found.';

        return view('terms', compact('terms'));
    }
}
