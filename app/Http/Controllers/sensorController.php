<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class sensorController extends Controller
{
    public function getSensor(){
      $sensors = DB::table('sensors')
       ->join('aviary', 'aviary.aviaryId', '=', 'sensors.aviaryId')
       ->select('sensors.sensorId', 'sensors.sensorName', 'sensors.maximum_limitation', 'sensors.minimum_limitation', 'aviary.name_aviary', 'sensors.value')
       ->get();
       return $sensors;
    }
    public function updateSensor(Request $request){
        DB::table('sensors')
        ->where('sensorId', $request->sensorId)
        ->update([
            'sensorName' => $request->sensorName,
            'maximum_limitation' => $request->maximum_limitation,
            'minimum_limitation' => $request->maximum_limitation,
            'aviaryId' => $request->aviaryId
        ]);

    }
    public function addSensor(Request $request){
        DB::table('status_history')
        ->insert([
               'userId' => $request->userId,
               'status' => "Добавил датчик",
               'aviaryId'=> $request->aviaryId
        ]);
        DB::table('sensors')
        ->insert([
            'sensorName' => $request->NameSensor,
            'maximum_limitation' => $request->maximum_limitation,
            'minimum_limitation' => $request->minimum_limitation,
            'aviaryId' => $request->aviaryId,
            'value' => "25"
        ]);

    }


    public function deleteSensor(Request $request){
        $dsap = DB::table('sensors')
        ->where('sensorId', $request->sensorId)
        ->get();
        DB::table('status_history')
        ->insert([
               'userId' => $request->userId,
               'status' => "Удалил датчик",
               'aviaryId'=> $dsap[0]->aviaryId
        ]);
        DB::table('sensors')
        ->where('sensorId', $request->sensorId)
        ->delete();
    }

}
