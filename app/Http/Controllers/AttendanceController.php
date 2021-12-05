<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function StartTime()
    {
        $user = Auth::user();
    }
}
