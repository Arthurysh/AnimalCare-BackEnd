<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
class UserController extends Controller
{
     public function addUser(Request $request){
        $request->validate([
            'name' => ['required'],
            'surname' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required','min:12', 'numeric', 'unique:users'],
        ]);

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
    
        for ($i = 0, $paslw = ''; $i < 12; $i++) {
            $index = rand(0, $count - 1);
            $paslw .= mb_substr($chars, $index, 1);
        }
        $data = ([
            'email' => $request->email,
            'password' => ($paslw),
        ]);
        Mail::to($request->email)->send(new WelcomeMail($data));
         DB::table('users')
         ->insert([
             'name' => $request->name,
             'surname' => $request->surname,
             'email' => $request->email,
             'phone' => $request->phone,
             'password' => Hash::make($paslw),
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
        ->select('email', 'name', 'surname', 'userId', 'phone')
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
