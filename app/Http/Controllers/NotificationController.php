<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
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
            ->select(
                'users.*',
                'projects.*',
                'applications.*',
                DB::raw('(SELECT email FROM users WHERE users.id = applications.user_id) as applicant_email')
            )
            ->orderBy('applications.created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'notifications' => $userData,
        ]);
    }

    /**
     * Mark all 'pending' applications as 'viewed' for the authenticated user's projects.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markApplicationsAsViewed(Request $request): RedirectResponse
    {
        $userId = auth::id(); // ✅ Use authenticated user

        try {
            $projectIds = Project::where('user_id', $userId)->pluck('id');

            if ($projectIds->isEmpty()) {
                return Redirect::back()->with('info', 'No applications to update.');
            }

            $updatedCount = Application::whereIn('project_id', $projectIds)
                ->where('application_status', 'pending')
                ->update(['application_status' => 'viewed']);

            return Redirect::back()->with('success', "$updatedCount application(s) marked as viewed.");
        } catch (\Throwable $e) {
            Log::error('Failed to mark applications as viewed: ' . $e->getMessage());

            return Redirect::back()->with('error', 'Something went wrong. Please try again.');
        }
    }
   
}