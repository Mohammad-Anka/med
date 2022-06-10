<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\Newt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});





Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/time', function () {
    // setlocale(LC_TIME, 'German');

    $dt = Carbon::now();

    return $dt;
});


Route::group(
    ['middleware' => ['auth', 'isadmin'], 'prefix' => 'Dashboard'],
    function () {
        Route::get('/', function () {
            return view('Admin.index');
        })->name('dashboard');
        Route::get('/users', [App\Http\Controllers\NotficationController::class, 'allUsers'])->name('users');
        Route::get('/news', [App\Http\Controllers\NewsController::class, 'all'])->name('news');
        Route::get('/nots', [App\Http\Controllers\NotficationController::class, 'all'])->name('nots');
        Route::get('/createnews', [App\Http\Controllers\NewsController::class, 'create'])->name('createNews');
        Route::get('/createnots', [App\Http\Controllers\NotficationController::class, 'create'])->name('createNots');
        Route::post('/storenots', [App\Http\Controllers\NotficationController::class, 'store'])->name('storeNots');
        Route::post('/storenews', [App\Http\Controllers\NewsController::class, 'store'])->name('storeNews');
        Route::post('/deletenots', [App\Http\Controllers\NotficationController::class, 'destroy'])->name('deleteNots');
        Route::post('/deletenews', [App\Http\Controllers\NewsController::class, 'destroy'])->name('deleteNews');


        Route::post('/updatenews/{id}', [App\Http\Controllers\NewsController::class, 'update'])->name('updateNews');
        Route::get('/editnews/{id}', [App\Http\Controllers\NewsController::class, 'edit'])->name('editNews');


        Route::post('/updatenots/{id}', [App\Http\Controllers\NotficationController::class, 'update'])->name('updateNots');
        Route::get('/editnots/{id}', [App\Http\Controllers\NotficationController::class, 'edit'])->name('editNots');
    }
);
