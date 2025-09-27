<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class userController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:2|max:50',
            'email' => 'required|string|max:50|unique:users,email',
            'account_type' => 'required|string',
            'google_id' => 'nullable',
            'password' => [
                Rule::requiredIf(!$request->filled('google_id')),
                'nullable',
                'min:6',
            ],
        ]);

        // Determine password: use dummy password if google_id exists
        $password = $request->google_id
            ? bcrypt(Str::random(16))
            : bcrypt($request->password);

        // Create user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'account_type' => $request->account_type,
            'google_id' => $request->google_id ?? null,
            'password' => $password,
        ]);

        // Create empty user profile for the user
        $user->profile()->create([
            'about' => null,
            'profile_picture' => null,
            'skills' => null,
            'occupation' => null,
            'user_status' => null,
            'address' => null,
        ]);

        if ($user) {
            return redirect()->route('login')->with('success', 'Account created successfully!');
        } else {
            // Redirect to landing page
            return redirect()->back();
        }
    }

    public function authenticate(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt authentication with validated data
        if (Auth::attempt($validatedData)) {
            Session::put('user_email', Auth::user()->email);
            Session::put('user_id', Auth::id());
            Session::put('user_role', Auth::user()->account_type);

            return redirect()->route('userProfile.show', ['id' => Auth::id()]);
        } else {
            return redirect()->back()->with('error', 'Invalid credentials!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication()
    {
        $userData = Socialite::driver('google')->user();

        return redirect()->route('register', [
            'google_id' => $userData->id,
            'email' => $userData->email,
            'name' => $userData->name,
            'avatar' => $userData->avatar,
        ]);
    }
}
