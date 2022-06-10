<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotficationController;
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

Route::controller(AuthController::class)->group(function () {
   
        Route::post('login', 'login');
        Route::post('forgotPassword', 'forgotPassword');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
});

/*
Route::post('getFirstNotification',[AuthController::class,'getFirstNotification'])->middleware('api');*/

define('pag',2);
define('pagadmin',5);
Route::group(
    ['middleware' => ['auth:api'],'prefix'=>'Notifications'],
    function () {
        Route::get('getNotificationByUser', [NotficationController::class, 'index']);
        Route::get('getNotification/{id}', [NotficationController::class, 'show']);
        Route::post('deleteNotification/{id}', [NotficationController::class, 'hideNotification']);
        Route::post('setAppointment', [NotficationController::class, 'setAppointment']);
    }
);


Route::group(
    ['middleware' => ['auth:api'],'prefix'=>'News'],
    function () {
        Route::get('getNews', [NewsController::class, 'index']);
        Route::get('getNews/{id}', [NewsController::class, 'show']);
        
    }
);
