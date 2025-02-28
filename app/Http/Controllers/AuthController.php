<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
{
    public function store(){
        request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)->max(14), 'confirmed'],
            'pin' =>['required', 'numeric', 'digits:4']
        ]);

        $user = User::create([
            'name' => request()->name,
            'email' => request()->email,
            'password' => Hash::make(request()->password)
        ]);

        Profile::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'profile_pin' => Hash::make(request()->pin),
            'role' => 'admin'
        ]);
        
        return redirect('/');
    }
}
