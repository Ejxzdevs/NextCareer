<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
{
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

    // Redirect back with a success message
    return redirect()->route('employer.project')->with('success', 'Project created successfully!');
}

}
