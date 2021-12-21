<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
     public function addUser(Request $request){
        $request->validate([
            'name' => ['required'],
            'surname' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required','min:12', 'numeric', 'unique:users'],
            'password' => ['required', 'min:8']
        ]);
         DB::table('users')
         ->insert([
             'name' => $request->name,
             'surname' => $request->surname,
             'email' => $request->email,
             'phone' => $request->phone,
             'password' => Hash::make($request->password),
             'user_role' => 'User'
         ]);
     }
     public function login(Request $request){
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json(Auth::user(), 200);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.']
        ]);
    }
    public function logout(Request $request){
        Auth::logout();
    }
    public function getUser(Request $request){
        $users = DB::table('users')
        ->where('user_role', 'User')
        ->get();
        return $users;
    }
    public function upateUser(Request $request){
        DB::table('users')
        ->where('userId', $request->userId)
        ->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
    }
    public function deleteUser(Request $userId){
        DB::table('users')
        ->where('userId', $userId->userId)
        ->delete();
    }
}
