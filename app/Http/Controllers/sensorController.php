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
       ->select('sensors.sensorId', 'sensors.sensorName', 'sensors.maximum_limitation', 'sensors.minimum_limitation', 'aviary.name')
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
        ]);

    }
}
