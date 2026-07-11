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
        return view('pages.policy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
