<?php

namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index (){

        $userId = auth::id();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $get_all_project_ids = Project::where('user_id', $userId)->pluck('id');
        $projects = Project::where('user_id', $userId)->with('applications')
        ->limit(3)
        ->get();
        // Count applications for the projects created by the authenticated user within the current week
        if ($get_all_project_ids->isEmpty()) {
            $totalWeeklyApplications = 0;
        } else {                       
        $applications = Application::whereIn('project_id', $get_all_project_ids)
            ->with('user')
            ->with('project')
            ->latest() 
            ->limit(8)
            ->get();

            
        $totalWeeklyApplications = Application::whereIn('project_id', $get_all_project_ids)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();
        }


        return Inertia::render('Employer/Dashboard', [
            'applications' => $applications,
            'projects' => $projects,
            'totalWeeklyApplications' => $totalWeeklyApplications,
        ]);
    }
}
