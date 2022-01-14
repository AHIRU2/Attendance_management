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

Route::get('/', [AttendanceController::class, 'LoginAttendance']);

Auth::routes();

Route::post('/attendance/start', [AttendanceController::class, 'punchIn'])->name('/attendance/start');
Route::post('/attendance/end', [AttendanceController::class, 'punchOut'])->name('/attendance/end');
Route::post('/attendance/reststart', [AttendanceController::class, 'restPunchIn'])->name('/attendance/reststart');
Route::post('/attendance/restend', [AttendanceController::class, 'restPunchOut'])->name('/attendance/restend');
Route::get('/attendance/attendance', [AttendanceController::class, 'AttendanceList'])->name('/attendance/attendance');
Route::post('/attendance/attendance', [AttendanceController::class, 'NextDay'])->name('attendance.next');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
