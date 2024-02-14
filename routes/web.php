<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('/adduser', [Controller::class, 'adduser'])->name('user.add');
Route::post('/2fa', [Controller::class, 'verifyuser'])->name('2fa');

// Route::middleware(['2fa'])->group(function () {
   
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Route::post('/2fa', function () {
    //     $validated = $request->validate([
    //         'one_time_password' => 'required',
    //     ]);

    //     $user = Auth::user();
    //     $google2fa = app('pragmarx.google2fa');
    //     $valid = $google2fa->verifyKey($user->google2fa_secret, $request->input('one_time_password'));
    //     return request();
    //     // return redirect(route('home'));
    // })->name('2fa');
// });
  
Route::get('/complete-registration', [RegisterController::class, 'completeRegistration'])->name('complete.registration');
