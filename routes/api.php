<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\TokenAuthController;
use \App\Http\Controllers\FileController;
use \App\Http\Controllers\TestController;
use \App\Http\Controllers\API\UtilController;

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


//start test
Route::get('register-user-test/{count}',[TestController::class,'RegisterUserTest']);
Route::get('create-directory-test/{count}',[TestController::class,'CreateDirectoryTest']);
Route::get('create-file-test/{count}',[TestController::class,'CreateFileTest']);
Route::get('time',function (){
    $mytime = Carbon\Carbon::now();
    return $mytime->toDateTimeString();});
//end test



Route::get('server-process',[UtilController::class,'Get_server_process']);

Route::group(['middleware' => ['guest']], function () {

    Route::post('/login', [TokenAuthController::class,'Login']);

    Route::post('/register', [TokenAuthController::class,'Register']);

});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('logout',[TokenAuthController::class,'Logout']);

    /////////////////////////

    Route::put('directory',[FileController::class,'Create_directory']);

    Route::put('file',[FileController::class,'Create_file']);

    Route::get('directory',[FileController::class,'Get_directories']);

    Route::get('file',[FileController::class,'Get_files']);

    Route::delete('directory',[FileController::class,'Delete_directories']);

    Route::delete('file',[FileController::class,'Delete_files']);

    //////////////////////////

});

