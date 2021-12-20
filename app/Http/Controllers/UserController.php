<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     public function addUser(Request $request){
         DB::table('user')
         ->insert([
             'firstName' => $request->firstName,
             'lastName' => $request->lastName,
             'email' => $request->email,
             'phone' => $request->phone,
             'password' => Hash::make($request->password),
             'role' => 'User'
         ]);
     }
}
