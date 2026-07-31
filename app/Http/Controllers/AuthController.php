<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    function logout(){
       Auth::logout();
       return redirect('/');
    }

    function loginPage(){
        return view('login');
    }

    function login(Request $request){

        $fields = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

       $user = Auth::attempt($fields);
       if(!$user){
          return back()->withErrors([
             'email' => 'The provided email or password is incorrect.',
          ]);
       }
       return redirect('/');
    }

    function register(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email', 'unique:users', 'max:255', 'min:4'],
            'name' => ['required', 'string', 'min:3', 'max:20'],
            'password' => ['required', 'min:8', 'max:20', 'confirmed']
        ]);

        $user = User::create($fields); // for example $user = User::create(['email' => $fields['email'], 'name' => $fields['name'], 'password' => bcrypt($fields['password'])]);
        Auth::login($user);
        return redirect('/');
    }

    function registerPage(){
        return view('register');
    }

}
