<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = collect();

        if (Auth::user()->account_type == 'employer') {
            // Fetch projects created by the authenticated employer
            $projects = Project::with('user.profile')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'asc')
                ->get();

            return Inertia::render('Employer/PostProject', [
                'projects' => $projects,
            ]);
        } elseif (Auth::user()->account_type == 'freelance') {
            // Fetch all projects for freelancers, ordered by latest
            $projects = Project::with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return Inertia::render('Freelance/Browse', [
                'projects' => $projects,
            ]);
        }

        // Fallback: redirect unauthorized or unknown users to login
        return redirect()->route('login');
    }

    public function store(Request $request)
    {
        // Ensure the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a project.');
        }

        // Only employers are allowed to create projects
        if (Auth::user()->account_type !== 'employer') {
            return redirect()->route('home')->with('error', 'You do not have permission to create a project.');
        }

        // Validate the incoming project data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'skills' => 'required|string',
            'budget' => 'required|numeric',
            'start_date' => 'required|date',
            'deadline' => 'required|date',
        ]);

        // Create the new project associated with the authenticated employer
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

        // Ensure the authenticated user is the project owner
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update the project with the new data
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

        // Ensure the authenticated user is the project owner
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the project
        $project->delete();

        return redirect()->route('employer.project')->with('success', 'Project deleted successfully!');
    }

    public function show($id,$application_id=null)
    {
        // Ensure the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $project = Project::with('user.profile')->find($id);

        // Mark this project as 'viewed'
        if ($application_id) {
        $application = Application::where('id', $application_id)
            ->where('project_id', $id)
            ->first();

        if ($application) {
            $application->update(['application_status' => 'viewed']);
            }
        }

        // Load all applications with associated user data
        $userApplication = Application::with('user.profile')->where('project_id', $id)->get();

        if (!$project) {
            return Inertia::render('Employer/ViewProject', [
                'error' => 'Project not found.',
                'project' => null,
            ]);
        }

        // Prevent employers from viewing others' projects
        if (Auth::user()->account_type === 'employer' && $project->user_id !== Auth::id()) {
            return Inertia::render('Employer/ViewProject', [
                'error' => 'You do not have permission to view this project.',
                'project' => null,
            ]);
        }

        // Render the project details page
        return Inertia::render('Employer/ProjectShow', [
            'project' => $project,
            'userApplication' => $userApplication,
            'error' => null,
        ]);
    }
}
