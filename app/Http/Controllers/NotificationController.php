<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

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
}
