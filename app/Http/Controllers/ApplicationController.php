<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        // Logic to display all applications
    }
    public function create()
    {
        // Logic to show the form for creating a new application
    }
    public function store(Request $request)
    {
        // Logic to store a new application
        // Validate the request data
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'link_portfolio' => 'nullable|url',
            'message' => 'required|string|max:1000',
        ]);

        // Store the application logic here
        Application::create([
        'user_id' => Auth::id(),   
        'project_id' => $request->project_id,
        'resume' => $request->file('resume')->store('resumes', 'public'),
        'link_portfolio' => $request->link_portfolio,
        'message' => $request->message,                       
        ]);

        return redirect()->route('freelance.browse')->with('success', 'Application submitted successfully.');
    }
    public function show($id)
    {
        // Logic to display a specific application
    }
}
