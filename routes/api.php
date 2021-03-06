<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/addUser', 'UserController@addUser');
Route::post('/login', 'UserController@login');
Route::post('/updateUser', 'UserController@updateUser');
Route::post('/deleteUser/{userId?}', 'UserController@deleteUser');
Route::post('/logout', 'UserController@logout');
Route::post('/updateSensor', 'sensorController@updateSensor');
Route::post('/updateAviary', 'aviaryController@updateAviary');
Route::post('/addAviary', 'aviaryController@addAviary');
Route::post('/deleteAviary', 'aviaryController@deleteAviary');
Route::post('/addAnimal', 'animalController@addAnimal');
Route::post('/deleteAnimal', 'animalController@deleteAnimal');
Route::post('/updateAnimal', 'animalController@updateAnimal');
Route::post('/updateStatus', 'aviaryController@updateStatus');
Route::post('/updateUserAviary', 'aviaryController@updateUserAviary');
Route::post('/addSensor', 'sensorController@addSensor');
Route::post('/deleteSensor', 'sensorController@deleteSensor');

Route::get('/getUser', 'UserController@getUser');
Route::get('/getSensor', 'sensorController@getSensor');
Route::get('/getAviary/{userId?}', 'aviaryController@getAviary');
Route::get('/getAnimal', 'animalController@getAnimal');
Route::get('/getStatusList', 'aviaryController@getStatusList');
