<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class aviaryController extends Controller
{
    public function getAviary(Request $userId){
        
     $user_role = DB::table('users')
        ->where('userId', $userId->userId)
        ->select('user_role')
        ->get();
     if($user_role = "User"){
        $userAviary = DB::table('user_aviary')
         ->join('users', 'user_aviary.userId', '=', 'users.userId')
         ->join('aviary', 'user_aviary.aviaryId', '=', 'aviary.aviaryId')
         ->where('user_aviary.userId', $userId->userId)
         ->get();

        $massiveAviaryId = [];
       
          foreach ($userAviary as $massiveAviary) {
            array_push($massiveAviaryId, $massiveAviary->aviaryId);
         }
         $aviaryPoint = DB::table('user_aviary')
         ->join('users', 'user_aviary.userId', '=', 'users.userId')
         ->join('aviary', 'user_aviary.aviaryId', '=', 'aviary.aviaryId')
         ->whereIn('user_aviary.aviaryId', $massiveAviaryId)
         ->get();
         $aviaryList = [];

         foreach ($aviaryPoint as $point) {
             $aviary = [
               "id" => $point->aviaryId,
               "name_aviary" => $point->name_aviary,
               "status" => $point->status,
               "users" => []
             ];
             
             foreach ($aviaryPoint as $key=>$pointId) {
                 if ($point->aviaryId == $pointId->aviaryId){
                    $user = [
                        "userId" => $pointId->userId,
                        "name" => $pointId->name,
                        "surname" => $pointId->surname
                      ];
                      array_push($aviary["users"], $user);
                 }
             }
             
             array_push($aviaryList, $aviary);
         }
         return array_unique($aviaryList, SORT_REGULAR);
     }
     elseif($user_role = "Admin"){
        $adminAviary = DB::table('user_aviary')
        ->join('users', 'user_aviary.userId', '=', 'users.userId')
        ->join('aviary', 'user_aviary.aviaryId', '=', 'aviary.aviaryId')
        ->get();
        return $adminAviary;
     }

    }
    public function addAviary(Request $request){

    }
    public function deleteAviary(Request $request){

    }
    public function updateAviary(Request $request){

    }
}
