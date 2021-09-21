<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\TokenAuthController;
use \App\Http\Controllers\FileController;
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

    //////////////////////////

});

