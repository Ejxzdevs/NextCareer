<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get projects and related applications for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNotifications(Request $request)
    {
        $userId = 3;

        // Get the latest 10 projects for the authenticated user, with all related applications
        $projectsWithApplications = Project::where('user_id', $userId)
                                           ->with('applications') // Load all fields of related applications
                                           ->orderByDesc('updated_at')
                                           ->limit(10)
                                           ->get();

        return response()->json([
            'projectsWithApplications' => $projectsWithApplications,
        ]);
    }
}
