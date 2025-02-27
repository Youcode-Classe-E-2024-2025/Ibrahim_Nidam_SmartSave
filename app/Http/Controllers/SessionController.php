<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function destroy(){
        Auth::logout();
        return redirect('/');
    }

    public function store(){
        $validation = request()->validate([
            'email'=>['required', 'email'],
            'password' => ['required']
        ]);

        if (!Auth::attempt($validation)){
            throw ValidationException::withMessages([
                'email'=>'Sorry, your email is wrong',
                'password'=>'Sorry, your password is wrong',
            ]);
        }

        request()->session()->regenerate();

        return redirect('/profiles');
    }
}
