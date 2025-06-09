<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // Show the settings page
    public function index()
    {
        // TODO: Load settings from database or config
        return view('admin.settings.index'); // create this blade view
    }

    // Update the settings
    public function update(Request $request)
    {
        // TODO: Validate and save settings

        // Example: validate some input
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'email' => 'required|email',
            // add more fields as needed
        ]);

        // Save settings logic here...

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }
}
