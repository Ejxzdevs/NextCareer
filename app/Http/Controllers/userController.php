<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class userController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:50',
            'password' => 'required|string|max:50',
            'account_type' => 'required|string|'
        ]);
        User::create([
            'email' => $request->email ,
            'password' => bcrypt($request->password),
            'account_type' => $request->account_type,
            ]
        );
        return redirect()->back()->with('success', 'Successfully registered!');
    }

    public function authenticate(Request $request){
        
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

        if(Auth::user()->account_type == 'employer'){
            return redirect()->route('employer.dashboard');
        } elseif(Auth::user()->account_type == 'freelance'){
            return redirect()->route('freelance.home');
        }
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
}
