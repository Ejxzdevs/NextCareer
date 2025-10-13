<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class GoogleAuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $type = $request->query('type');
        session(['google_auth_type' => $type]);
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication(Request $request)
    {
        $userData = Socialite::driver('google')->user();
        $type = session('google_auth_type');

        // Redirect back with Google data as query params
        if($type === 'register'){
            return redirect()->route('register', [
                'google_id' => $userData->id,
                'email' => $userData->email,
                'name' => $userData->name,
                'avatar' => $userData->avatar,
            ]);
        }else{
            $google_id = $userData->id;
            $user = User::where('google_id', $google_id)->first();
            $user_id =  $user->id;

            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();   
                return redirect()->route('userProfile.show', ['id' => $user_id]);
            } else {
                return redirect()->route('login')->with('error', 'Google account not linked.');
            }
        }
    }
}
