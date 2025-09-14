<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $user_role = Auth::user()->account_type;
        $userId = auth::id();
        if($user_role === 'employer'){
        $get_all_projects = Project::where('user_id', $userId)->get();
        $project_ids = $get_all_projects->pluck('id');

        $applications = Application::whereIn('project_id', $project_ids)
            ->with(['user.profile','project'])
            ->latest() 
            ->paginate(3);

         return Inertia::render('Employer/Application', [
            'applications' => $applications,
            'projects' =>  $get_all_projects,
        ]);
        }
        else{
      $get_all_applications = Application::where('applications.user_id', $userId)
        ->with('project.user.profile')
        ->orderBy('applications.created_at', 'desc')
        ->paginate(3);


            return Inertia::render('Freelance/Application',[
                'applications' => $get_all_applications
            ]);
        }
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

    // update application
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
        'application_status' => 'required|string|in:pending,viewed,cancelled,scheduled,hired,rejected'
    ]);

        $application = Application::findOrFail($id);
        $application->application_status = $request->application_status;
        $application->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
