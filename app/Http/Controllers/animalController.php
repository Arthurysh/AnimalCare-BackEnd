<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class animalController extends Controller
{
    public function getAnimal(){
       $animals = DB::table('animal')
       ->join('aviary', 'animal.aviaryId', '=', 'aviary.aviaryId')
       ->select('aviary.aviaryId', 'aviary.name_aviary', 'animal.animalId', 'animal.name', 'animal.type', 'animal.birthday', 'animal.image')
       ->get();
        return $animals;
    }
    public function updateAnimal(Request $request){
        DB::table('animal')
        ->where('animalId', $request->animalId)
        ->update([
            'name' => $request->name,
            'aviaryId' => $request->aviaryId,
            'type' => $request->type,
            'birthday' => $request->birthday,
       ]);
    }
    public function deleteAnimal(Request $request){
       $animalIdDelete = DB::table('animal')
        ->where('animalId', $request->idAnimal)
        ->get();
   
        DB::table('status_history')
        ->insert([
               'userId' => $request->userId,
               'status' => "Удалил животного",
               'aviaryId'=> $animalIdDelete[0]->aviaryId
        ]);

        DB::table('animal')
        ->where('animalId', $request->idAnimal)
        ->delete();
    }
    public function addAnimal(Request $request){
        DB::table('status_history')
        ->insert([
               'userId' => $request->userId,
               'status' => "Добавил животного",
               'aviaryId'=> $request->aviary

        ]);
       DB::table('animal')
       ->insert([
            'name' => $request->name,
            'aviaryId' => $request->aviary,
            'type' => $request->type,
            'birthday' => $request->birthday,
            'image' => $request->image
       ]);
    }
}
