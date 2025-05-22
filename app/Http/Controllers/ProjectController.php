<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use Illuminate\Http\Request;

class ProjectController extends Controller
    {
    public function index()
        {
            $projects = Project::where('user_id', Auth::id())->get();

            return Inertia::render('Employer/PostProject', [
                'projects' => $projects,
            ]);
    }
    public function store(Request $request)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a project.');
        }

        // Check if the user is an employer
        if (Auth::user()->account_type !== 'employer') {
            return redirect()->route('home')->with('error', 'You do not have permission to create a project.');
        }
    // Validate the incoming data
    $request->validate([
        'title' => 'required|string|max:255',      
        'description' => 'required|string',         
        'category' => 'required|string',            
        'skills' => 'required|string',             
        'budget' => 'required|numeric',          
        'start_date' => 'required|date',       
        'deadline' => 'required|date',      
    ]);

    // Create a new project
    Project::create([
        'user_id' => Auth::id(),               
        'title' => $request->title,             
        'description' => $request->description,  
        'category' => $request->category,        
        'skills' => $request->skills,             
        'budget' => $request->budget,    
        'start_date' => $request->start_date,    
        'deadline' => $request->deadline,
    ]);

    return redirect()->route('employer.project')->with('success', 'Project created successfully!');
    
    }
}
