<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    //勤務開始処置
    public function punchIn()
    {
        $user = Auth::user();

        // 打刻は１日一回まで
        $oldTimestamp = Attendance::where('user_id', $user->id)->latest()->first();
        if ($oldTimestamp) {
            $oldTimestampPunchIn = new Carbon($oldTimestamp->start_time);

            $oldTimestampDay = $oldTimestampPunchIn->startOfDay();
        }

        $newTimestampDay = Carbon::today();

        //同日付の出勤打刻で、かつ直前のTimestampの退勤打刻がされていない場合エラーを吐き出す。
        if ((isset($oldTimestampDay) == $newTimestampDay) && (empty($oldTimestamp->end_time))) {
            return redirect()->back()->with('error', 'すでに出勤打刻がされています。');
        }

        $timestamp = Attendance::create([
            'user_id' => $user->id,
            'start_time' => Carbon::now(),
        ]);

        return redirect()->back()->with('my_status', '出勤打刻が完了しました。');
    }

    //退勤処理
    public function punchOut()
    {
        $user = Auth::user();
        $timestamp = Attendance::where('user_id', $user->id)->latest()->first();

        if (!empty($timestamp->punchOut)) {
            return redirect()->back()->with('error', '既に退勤の打刻がされているか、出勤が打刻されていません。');
        } else {
            $timestamp->update([
                'end_time' => Carbon::now()
            ]);

            return redirect()->back()->with('my_status', '退勤打刻が完了しました。');
        }
    }


    //休憩開始処理
    public function restPunchIn()
    {
        $user = Auth::user();

        $startattendance = Attendance::where('user_id', $user->id)->latest()->first();

        $timestamp = Rest::create([
            'user_id' => $user->id,
            'attendance_id' => $startattendance->id,
            'start_time' => Carbon::now(),
        ]);

        return redirect()->back()->with('my_status', '休憩開始打刻が完了しました。');
    }

    //休憩終了処理
    public function restPunchOut()
    {
        $user = Auth::user();
        $timestamp = Rest::where('user_id', $user->id)->latest()->first();
        $startattendance = Attendance::where('user_id', $user->id)->latest()->first();

        if (!empty($timestamp->punchOut)) {
            return redirect()->back()->with('error', '既に休憩終了の打刻がされているか、休憩開始が打刻されていません。');
        }

        $timestamp->update([
            'end_time' => Carbon::now()
        ]);

        $timestamp = Rest::where('user_id', $user->id)->latest()->first();

        $data = Rest::select(DB::raw('TIMEDIFF(start_time,end_time)'))->where($timestamp)->get();

        $startattendance->update([
            'rest_time' => $data->time
        ]);

        return redirect()->back()->with('my_status', '休憩終了時間打刻が完了しました。');
    }

    public function culcRestTime()
    {
    }
}
