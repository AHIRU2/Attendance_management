<?php

use App\Http\Controllers\AttendanceController;
use App\Models\Attendance;
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
    return view('index');
});

Auth::routes();

Route::post('/attendance/start', [AttendanceController::class, 'punchIn'])->name('/attendance/start');
Route::post('/attendance/end', [AttendanceController::class, 'punchOut'])->name('/attendance/end');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
