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
        $rest = Rest::where('user_id', $user->id)->latest()->first();

        if ($timestamp  == null) {
            $startFlg = true;
            $endFlg = false;
            $startRestFlg = false;
            $endRestFlg = false;

            $startFlg_json = json_encode($startFlg);
            $endFlg_json = json_encode($endFlg);
            $startRestFlg_json = json_encode($startRestFlg);
            $endRestFlg_json = json_encode($endRestFlg);

            return view('index', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
        } else if ($timestamp->start_time != null && $date == date("Y-m-d", strtotime($timestamp->start_time)) && $timestamp->end_time == null) {
            if ($date != date("Y-m-d", strtotime($rest->start_time))) {
                $startFlg = false;
                $endFlg = true;
                $startRestFlg = true;
                $endRestFlg = false;

                $startFlg_json = json_encode($startFlg);
                $endFlg_json = json_encode($endFlg);
                $startRestFlg_json = json_encode($startRestFlg);
                $endRestFlg_json = json_encode($endRestFlg);

                return view('index', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
            } else if ($date == date("Y-m-d", strtotime($rest->start_time)) && $rest->end_time == null) {
                $startFlg = false;
                $endFlg = true;
                $startRestFlg = false;
                $endRestFlg = true;

                $startFlg_json = json_encode($startFlg);
                $endFlg_json = json_encode($endFlg);
                $startRestFlg_json = json_encode($startRestFlg);
                $endRestFlg_json = json_encode($endRestFlg);

                return view('index', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
            } else if ($date == date("Y-m-d", strtotime($rest->start_time)) && $rest->end_time != null) {
                $startFlg = false;
                $endFlg = true;
                $startRestFlg = true;
                $endRestFlg = false;

                $startFlg_json = json_encode($startFlg);
                $endFlg_json = json_encode($endFlg);
                $startRestFlg_json = json_encode($startRestFlg);
                $endRestFlg_json = json_encode($endRestFlg);

                return view('index', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
            }
        } else {

            $lastEndTime = $timestamp->end_time;
            $lastDateTime = $timestamp->start_time;
            $lastDate = date("Y-m-d", strtotime(($lastDateTime)));
            $nextdate = date("Y-m-d", strtotime($lastDateTime . "+1 day"));

            //勤怠開始してから日付を跨いだ場合、勤怠開始時と同日の23:59:59をend_timeに挿入し、ログイン時の日時をstart_timeへ挿入
            while ($lastEndTime == null && $lastDate != $date) {

                $timestamp->update([
                    'end_time' => Carbon::parse($lastDateTime)->endOfDay()
                ]);

                $timestamp = Attendance::create([
                    'user_id' => $user->id,
                    'start_time' => $nextdate . ' 00:00:00',
                    'rest_time' => '00:00:00'
                ]);

                $timestamp = Attendance::where('user_id', $user->id)->latest()->first();
                $lastEndTime = $timestamp->end_time;
                $lastDateTime = $timestamp->start_time;
                $lastDate = date("Y-m-d", strtotime(($lastDateTime)));
                $nextdate = date("Y-m-d", strtotime($lastDateTime . "+1 day"));
            }

            $startFlg = false;
            $endFlg = true;
            $startRestFlg = true;
            $endRestFlg = false;

            $startFlg_json = json_encode($startFlg);
            $endFlg_json = json_encode($endFlg);
            $startRestFlg_json = json_encode($startRestFlg);
            $endRestFlg_json = json_encode($endRestFlg);

            return view('index', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
        }
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

        $startFlg = false;
        $endFlg = true;
        $startRestFlg = true;
        $endRestFlg = false;

        $startFlg_json = json_encode($startFlg);
        $endFlg_json = json_encode($endFlg);
        $startRestFlg_json = json_encode($startRestFlg);
        $endRestFlg_json = json_encode($endRestFlg);



        return redirect()->back()->with('my_status', '出勤打刻が完了しました。', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
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

            $startFlg = false;
            $endFlg = false;
            $startRestFlg = false;
            $endRestFlg = false;

            $startFlg_json = json_encode($startFlg);
            $endFlg_json = json_encode($endFlg);
            $startRestFlg_json = json_encode($startRestFlg);
            $endRestFlg_json = json_encode($endRestFlg);

            return redirect()->back()->with('my_status', '退勤打刻が完了しました。', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
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

        $startFlg = false;
        $endFlg = true;
        $startRestFlg = false;
        $endRestFlg = true;

        $startFlg_json = json_encode($startFlg);
        $endFlg_json = json_encode($endFlg);
        $startRestFlg_json = json_encode($startRestFlg);
        $endRestFlg_json = json_encode($endRestFlg);

        return redirect()->back()->with('my_status', '休憩開始打刻が完了しました。', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
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

        $startFlg = false;
        $endFlg = true;
        $startRestFlg = true;
        $endRestFlg = false;

        $startFlg_json = json_encode($startFlg);
        $endFlg_json = json_encode($endFlg);
        $startRestFlg_json = json_encode($startRestFlg);
        $endRestFlg_json = json_encode($endRestFlg);

        return redirect()->back()->with('my_status', '休憩終了時間打刻が完了しました。', compact('startFlg_json', 'endFlg_json', 'startRestFlg_json', 'endRestFlg_json'));
    }

    public function AttendanceList(Request $request)
    {
        if ($request->page) {
            $date = $request->date; //現在指定している日付を取得
        } else {
            $date = Carbon::today()->format("Y-m-d");
        }
        $user = Auth::user();
        $items = Attendance::Join('users', 'attendance.user_id', '=', 'users.id')->whereDate('start_time', $date)->Paginate(5);
        $items->appends(compact('date')); //日付を渡す
        return view('attendance', ['today' => $date], compact('date', 'items'));
    }

    public function NextDay(Request $request)
    {
        $nowdate = $request->input('today');
        $dayflg = $request->input('dayflg');

        if ($dayflg == "next") {
            $date = date("Y-m-d", strtotime($nowdate . "+1 day"));
        } else if ($dayflg == "back") {
            $date = date("Y-m-d", strtotime($nowdate . "-1 day"));
        }

        $user = Auth::user();
        $items = Attendance::Join('users', 'attendance.user_id', '=', 'users.id')->whereDate('start_time', $date)->Paginate(5);
        $items->appends(compact('date')); // 日付を渡す
        return view('attendance', ['today' => $date], compact('date', 'items'));
    }
}
