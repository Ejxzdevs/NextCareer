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
        $projects = collect();

        if (Auth::user()->account_type == 'employer') {
            $projects = Project::with('user')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'asc')
                ->get();
            return Inertia::render('Employer/PostProject', [
                'projects' => $projects,
            ]);
        } elseif (Auth::user()->account_type == 'freelance') {
            // For freelance users, fetch all projects with their associated users
            $projects = Project::with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return Inertia::render('Freelance/Browse', [
                'projects' => $projects,
            ]);
        }

        // Fallback for unexpected account types
        return redirect()->route('login');
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

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Check if the authenticated user owns the project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update the project fields
        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'skills' => $request->skills,
            'budget' => $request->budget,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('employer.project')->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
    
        $project = Project::findOrFail($id);

        // Check if the authenticated user owns the project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $project->delete();

        return redirect()->route('employer.project')->with('success', 'Project deleted successfully!');
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $project = Project::with('user')->find($id);

        if (!$project) {
            return Inertia::render('Employer/ViewProject', [
                'error' => 'Project not found.',
                'project' => null,
            ]);
        }

        if (Auth::user()->account_type === 'employer' && $project->user_id !== Auth::id()) {
            return Inertia::render('Employer/ViewProject', [
                'error' => 'You do not have permission to view this project.',
                'project' => null,
            ]);
        }

        return Inertia::render('Employer/ViewProject', [
            'project' => $project,
            'error' => null,
        ]);
    }
}
