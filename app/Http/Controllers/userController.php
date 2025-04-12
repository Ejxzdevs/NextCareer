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
            'password' => $request->password,
            'account_type' => $request->account_type,
            ]
        );
        return redirect()->route('login')->with('success', 'User Registered! Please login.');
    }
}
