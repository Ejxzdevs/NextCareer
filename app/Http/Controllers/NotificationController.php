<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Application;
use App\Models\Project;


class NotificationController extends Controller
{
    /**
     * Get projects and related applications with applicant info for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNotifications(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        $userData = User::where('users.id', $userId)
            ->join('projects', 'users.id', '=', 'projects.user_id')
            
            ->join('applications', 'projects.id', '=', 'applications.project_id')
            ->join('user_profiles', 'applications.user_id', '=', 'user_profiles.user_id')
            ->select(
                'users.*',
                'projects.*',
                'applications.id as application_id',
                'applications.*',
                'user_profiles.*',
                DB::raw('(SELECT email FROM users WHERE users.id = applications.user_id) as applicant_email')
            )
            ->orderBy('applications.created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $userData,
        ]);
    }

    /**
     * Mark all applications for the authenticated user's projects as 'view'.
     * Only applications with 'pending' status will be updated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $userId = auth::id();

        try {
            $user_project_id = Project::where('user_id', $userId)->pluck('id');

            if ($user_project_id->isEmpty()) {
                return response()->json([
                    'message' => 'No applications to update for your projects.',
                    'updated_count' => 0,
                ]);
            }
            $updatedCount = Application::whereIn('project_id',     $user_project_id)
                                      ->where('application_status', 'pending')
                                      ->update(['application_status' => 'viewed']);

            return response()->json([
                'message' => 'Applications marked as viewed successfully.',
                'updated_count' => $updatedCount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to mark applications as viewed.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}