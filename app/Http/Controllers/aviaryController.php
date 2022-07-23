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
         
     if($user_role[0]->user_role == "User"){
        $userAviary = DB::table('user_aviary')
         ->join('users', 'user_aviary.userId', '=', 'users.userId')
         ->join('aviary', 'user_aviary.aviaryId', '=', 'aviary.aviaryId')
         ->where('user_aviary.userId', $userId->userId)
         ->get();

        $massiveAviaryId = [];


        $sensorMassive = DB::table('sensors')
         ->join('aviary', 'sensors.aviaryId', '=', 'aviary.aviaryId')
         ->get();
         
        foreach($sensorMassive as $key=>$sensor){
            
                if($sensor->value > $sensor->maximum_limitation){
                   DB::table('aviary')
                   ->where('aviary.aviaryId', $sensor->aviaryId)
                   ->update(['status' => 'Температура ниже/выше нормы!']);
                   
                   
                }
                elseif($sensor->value < $sensor->minimum_limitation){
                    DB::table('aviary')
                    ->where('aviary.aviaryId', $sensor->aviaryId)
                    ->update(['status' => 'Температура ниже/выше нормы!']);
                }
                else {
                    DB::table('aviary')
                   ->where('aviary.aviaryId', $sensor->aviaryId)
                   ->update(['status' => 'Все в норме']);
                }
        }

       
          foreach ($userAviary as $massiveAviary) {
            array_push($massiveAviaryId, $massiveAviary->aviaryId);
         }
         $aviaryPoint = DB::table('user_aviary')
         ->join('users', 'user_aviary.userId', '=', 'users.userId')
         ->join('aviary', 'user_aviary.aviaryId', '=', 'aviary.aviaryId')
         ->whereIn('user_aviary.aviaryId', $massiveAviaryId)
         ->get();
         $animalsMassive = DB::table('animal')->get();
         $statusHisoryMassive = DB::table('status_history')
         ->join('users', 'status_history.userId', '=', 'users.userId')
         ->get();
         $aviaryList = [];
         $sensorMassive = DB::table('sensors')
         ->join('aviary', 'sensors.aviaryId', '=', 'aviary.aviaryId')
         ->get();
         foreach ($aviaryPoint as $point) {
             $aviary = [
               "id" => $point->aviaryId,
               "name_aviary" => $point->name_aviary,
               "status" => $point->status,
               "users" => [],
               "animals" => [],
               "status_history" => []
             ];
             
             foreach ($aviaryPoint as $key=>$pointId) {
                 if ($point->aviaryId == $pointId->aviaryId){
                    $user = [
                        "userId" => $pointId->userId,
                        "name" => $pointId->name,
                        "surname" => $pointId->surname,
                        "phone" =>$pointId->phone,
                        "email" => $pointId->email

                      ];
                      array_push($aviary["users"], $user);
                    }
                }
                 foreach ($animalsMassive as $key=>$animalId) {
                    if ($point->aviaryId == $animalId->aviaryId){
                       $animalPoint = [
                           "animalId" => $animalId->animalId,
                           "name" => $animalId->name,
                           "type" => $animalId->type
                         ];
                         array_push($aviary["animals"], $animalPoint);
                        }
                }
                foreach ($statusHisoryMassive as $key=>$statusId) {
                    if ($point->aviaryId == $statusId->aviaryId){
                       $statusPoint = [
                           "status" => $statusId->status,
                           "timeStatus" => $statusId->timeStatus,
                           "userId" => $statusId->userId,
                           "name" => $statusId->name,
                           "surname" => $statusId->surname
                         ];
                        

                         array_push($aviary["status_history"], $statusPoint);
                        }
                }
             
             
             array_push($aviaryList, $aviary);
         }
         
       
         function super_unique($array,$key)
         {
            $temp_array = [];
            foreach ($array as &$v) {
                if (!isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] =& $v;
            }
            $array = array_values($temp_array);
            return $array;
     
         }
         return(super_unique($aviaryList,'id'));
     }
     elseif($user_role[0]->user_role == "Admin"){
        $userAviary = DB::table('user_aviary')
         ->join('users', 'user_aviary.userId', '=', 'users.userId')
         ->join('aviary', 'user_aviary.aviaryId', '=', 'aviary.aviaryId')
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
         $animalsMassive = DB::table('animal')->get();
         $statusHisoryMassive = DB::table('status_history')
         ->join('users', 'status_history.userId', '=', 'users.userId')
         ->get();
         $aviaryList = [];
         foreach ($aviaryPoint as $point) {
             $aviary = [
               "id" => $point->aviaryId,
               "name_aviary" => $point->name_aviary,
               "status" => $point->status,
               "users" => [],
               "animals" => [],
               "status_history" => []
             ];
             
             foreach ($aviaryPoint as $key=>$pointId) {
                 if ($point->aviaryId == $pointId->aviaryId){
                    $user = [
                        "userId" => $pointId->userId,
                        "name" => $pointId->name,
                        "surname" => $pointId->surname,
                        "phone" =>$pointId->phone,
                        "email" => $pointId->email
                      ];
                      array_push($aviary["users"], $user);
                    }
                }
                 foreach ($animalsMassive as $key=>$animalId) {
                    if ($point->aviaryId == $animalId->aviaryId){
                       $animalPoint = [
                           "animalId" => $animalId->animalId,
                           "name" => $animalId->name,
                           "type" => $animalId->type
                         ];
                         array_push($aviary["animals"], $animalPoint);
                        }
                }
                foreach ($statusHisoryMassive as $key=>$statusId) {
                    if ($point->aviaryId == $statusId->aviaryId){
                       $statusPoint = [
                        "status" => $statusId->status,
                        "timeStatus" => $statusId->timeStatus,
                        "userId" => $statusId->userId,
                        "name" => $statusId->name,
                        "surname" => $statusId->surname
                         ];
                         array_push($aviary["status_history"], $statusPoint);
                        }
                }
             
             
             array_push($aviaryList, $aviary);
         }
         function super_unique($array,$key)
         {
            $temp_array = [];
            foreach ($array as &$v) {
                if (!isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] =& $v;
            }
            $array = array_values($temp_array);
            return $array;
     
         }
         return(super_unique($aviaryList,'id'));
     }

    }
    public function addAviary(Request $request){

       
        

        DB::table('aviary')
        ->insert([
            'name_aviary' => $request->name_aviary,
            'status'=> "Все в норме"
        ]);

        $lastIdAviary = DB::table('aviary')
        ->latest('aviaryId')
        ->value('aviaryId'); 
        DB::table('status_history')
        ->insert([
               'userId' => $request->userId,
               'status' => "Создан вольер",
               'aviaryId'=> $lastIdAviary

        ]);
        DB::table('user_aviary')
        ->insert([
            'userId' => $request->userId,
            'aviaryId'=> $lastIdAviary
        ]);
        
        
    }
    public function deleteAviary(Request $request){
        DB::table('status_history')
        ->insert([
               'userId' => $request->userId,
               'status' => "Удален вольер",
               'aviaryId'=> $request->aviaryId

        ]);
        DB::table('user_aviary')
        ->where('aviaryId', $request->aviaryId)
        ->delete();
        DB::table('aviary')
        ->where('aviaryId', $request->aviaryId)
        ->delete();

        
        
    }
    public function updateAviary(Request $request){
        DB::table('user_aviary')
        ->where('aviaryId', $request->aviaryId)
        ->delete();
        $userAviaryUpdate = $request->users;
        
        foreach ($userAviaryUpdate as $pointAviaryUpdate) {
            DB::table('user_aviary')
            ->insert([
            'aviaryId' => $request->aviaryId,
            'userId' => $pointAviaryUpdate['userId'],
            ]);
        }
        DB::table('aviary')
        ->where('aviaryId', $request->aviaryId)
        ->update([
            'name_aviary' => $request->name_aviary,
            'status'=> $request->status
        ]);
    }
    public function updateStatus(Request $request) {
        DB::table('status_history')
        ->insert([
            'aviaryId' => $request->aviaryId,
            'status' => $request->status,
            'userId' => $request->userId
        ]);
    }
    public function updateUserAviary(Request $request){
        DB::table('status_history')
        ->insert([
               'userId' => $request->userMain,
               'status' => "Добавил пользователя",
               'aviaryId'=> $request->aviaryId

        ]);
        DB::table('user_aviary')
        ->insert([
            'aviaryId' => $request->aviaryId,
            'userId' => $request->userId
        ]);
    }
    public function getStatusList(){
       return DB::table('status_history')
       ->join('aviary', 'aviary.aviaryId', '=', 'status_history.aviaryId')
       ->join('users', 'users.userId', '=', 'status_history.userId')
       ->select('status_history.status', 'aviary.name_aviary', 'users.name', 'users.surname', 'status_history.timeStatus')
       ->orderBy('timeStatus', 'desc')
       ->get();
    }
}
