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
    public function LoginAttendance()
    {
        $user = Auth::user();
        $date = Carbon::today()->format("Y-m-d");
        $timestamp = Attendance::where('user_id', $user->id)->latest()->first();
        $lastEndTime = $timestamp->end_time;
        $lastDateTime = $timestamp->start_time;
        $lastDate = date("Y-m-d", strtotime(($lastDateTime)));

        //var_dump($lastDate, $date);

        //勤怠開始してから日付を跨いだ場合、勤怠開始時と同日の23:59:59をend_timeに挿入し、ログイン時の日時をstart_timeへ挿入
        if ($lastEndTime == null && $lastDate != $date) {
            $timestamp->update([
                'end_time' => Carbon::parse($lastDateTime)->endOfDay()
            ]);

            $timestamp = Attendance::create([
                'user_id' => $user->id,
                'start_time' => Carbon::today(),
                'rest_time' => '00:00:00'
            ]);
        }

        return view('index');
    }


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
            'rest_time' => '00:00:00'
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

            $data = Attendance::select(DB::raw('timediff(end_time,start_time) as attendancetime'))->where('id', $timestamp->id)->value('attendancetime');

            print($data);

            $timestamp->update([
                'attendance_time' => $data
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
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();

        if (!empty($timestamp->punchOut)) {
            return redirect()->back()->with('error', '既に休憩終了の打刻がされているか、休憩開始が打刻されていません。');
        }

        $timestamp->update([
            'end_time' => Carbon::now()
        ]);

        //休憩時間取得
        $timestamp = Rest::where('user_id', $user->id)->latest()->first();

        // print($timestamp);

        $data = Rest::select(DB::raw('timediff(end_time,start_time) as resttime'))->where('id', $timestamp->id)->value('resttime');

        // print($data);

        //休憩時間合算
        //attendanceテーブルの休憩時間現在値取得
        // print($timestamp->id);
        $restdata = Attendance::select(DB::raw('rest_time'))->where('id', $timestamp->attendance_id)->value('rest_time');

        // print($restdata);


        $sum = DB::select('select addtime(:restdata, :data) as sum', ['restdata' => $restdata, 'data' => $data]);

        // var_dump($sum);
        print($sum[0]->sum);

        $attendance->update([
            'rest_time' => $sum[0]->sum
        ]);

        return redirect()->back()->with('my_status', '休憩終了時間打刻が完了しました。');
    }

    // public function AttendanceList(Request $request)
    // {
    //     $user = Auth::user();
    //     $date = Carbon::today()->format("Y-m-d");
    //     $items = Attendance::Join('users', 'attendance.user_id', '=', 'users.id')->where('attendance.user_id', '=', $user->id)->pagenate(5);
    //     // $items = DB::table('attendance')->join('users', 'attendance.user_id', '=', 'users.id')->where('user_id', $user->id)->get();
    //     print($items);
    //     // $timestamps = Attendance::where('user_id', $user->id)->where('start_time', $date);
    //     //SQL->select * from attendance where date_format(start_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d');
    //     // $attendance_data = Attendance::select('start_time')->get();
    //     // $users = Attendance::Join('users', 'attendance.user_id', '=', 'users.id')->where('start_taime', $date)->get();
    //     // $items = Attendance::Pagenate(5);
    //     // return view('attendance', compact('users', 'date', 'items', 'attendance_date'));
    //     // dd($timestamps);
    //     return view('attendance', ['today' => $date], ['items', $items]);
    // }

    public function AttendanceList(Request $request)
    {
        $user = Auth::user();
        $date = Carbon::today()->format("Y-m-d");
        $items = Attendance::Join('users', 'attendance.user_id', '=', 'users.id')->whereDate('start_time', $date)->Paginate(10);
        return view('attendance', ['today' => $date], compact('items'));
    }
}
