<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return \Inertia\Response
     */
    public function showProfile()
    {
        $userId = Auth::id();
        
        $profile = UserProfile::with('user:id,email')
            ->where('user_id', $userId)
            ->first();

        return inertia('Employer/Profile', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
{
      
    $validated = $request->validate([
        'skills' => 'array',
        'skills.*' => 'string|max:55',
        'address' => 'nullable|string|max:155',
        'occupation' => 'nullable|string|max:50',
        'about' => 'nullable|string|max:1000',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
    ]);

    $userId = Auth::id();

    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $validated['profile_picture'] = '/storage/' . $path;
    } else {
        unset($validated['profile_picture']);
    }

    UserProfile::updateOrCreate(
        ['user_id' => $userId],
        $validated
    );

    return redirect()->route('employer.profile')
        ->with('success', 'Profile updated successfully.');
    }
}