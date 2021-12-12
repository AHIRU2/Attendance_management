<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function punchIn()
    {
        $user = Auth::user();

        // 打刻は１日一回まで
        $oldTimestamp = Attendance::where('user_id', $user->id)->latest()->first();
        if ($oldTimestamp) {
            $oldTimestampPunchIn = new Carbon($oldTimestamp->punchIn);
            $oldTimestampDay = $oldTimestampPunchIn->startOfDay();
        }

        $newTimestampDay = Carbon::today();

        // 同日付の出勤打刻で、かつ直前のTimestampの退勤打刻がされていない場合エラーを吐き出す。
        // if (($oldTimestampDay == $newTimestampDay) && (empty($oldTimestamp->punchOut))) {
        //     return redirect()->back()->with('error', 'すでに出勤打刻がされています。');
        // }

        $timestamp = Attendance::create([
            'user_id' => $user->id,
            'start_time' => Carbon::now(),
        ]);

        return redirect()->back()->with('my_status', '出勤打刻が完了しました。');
    }

    public function punchOut()
    {
        $user = Auth::user();
        $timestamp = Attendance::where('user_id', $user->id)->latest()->first();

        if (!empty($timestamp->punchOut)) {
            return redirect()->back()->with('error', '既に退勤の打刻がされているか、出勤が打刻されていません。');
        }

        $timestamp->update([
            'end_time' => Carbon::now()
        ]);

        return redirect()->back()->with('my_status', '退勤打刻が完了しました。');
    }
}
