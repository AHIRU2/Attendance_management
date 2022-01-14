@extends('layouts.app')

@section('content')
<link href="{{ asset('css/attendance.css') }}" rel="stylesheet">

<div　class="daycontroller">
    <form action="attendance" method="post">
        @csrf
        <input type="hidden" class="form-control" id="today" name="today" value={{ $today }}>
        <input type="hidden" class="flg" name="dayflg" value="back">
        <input type="submit" name="" value="<" class="day-list" id="back_btn">
    </form>
    <div class="day-list" id="date_txt">{{ $today }}</div>
    <form action="attendance" method="post">
        @csrf
        <input type="hidden" class="form-control" id="today" name="today" value={{ $today }}>
        <input type="hidden" class="flg" name="dayflg" value="next">
        <input type="submit" name="" value=">" class="day-list" id="next_btn">
    </form>
</div>

<table>
    <tr>
        <th>名前</th>
        <th>勤務開始</th>
        <th>勤務終了</th>
        <th>休憩時間</th>
        <th>勤務時間</th>
    </tr>
    @foreach($items as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ substr($item->start_time,10) }}</td>
        <td>{{ substr($item->end_time,10) }}</td>
        <td>{{ $item->rest_time }}</td>
        <td>{{ $item->attendance_time }}</td>
    </tr>
    @endforeach
</table>
{{ $items->appends($today)->links()}}
@endsection
<script>
    // var cnt = 0;

    // function showMonthDate() {
    //     var nowDate = new Date();
    //     var myDate = new Date(nowDate.getTime() + 86400000 * cnt);
    //     var mm = ("0" + (myDate.getMonth() + 1)).slice(-2);
    //     var dd = ("0" + (myDate.getDate())).slice(-2);
    //     document.getElementById("date_txt").value = mm + "月" + dd + "日";
    // }

    // //関数 showMonthDateを即実行
    // showMonthDate();

    // //前日ボタンクリック時のイベント
    // document.getElementById("back_btn").onclick = function() {
    //     cnt--;
    //     showMonthDate();
    // }

    // //次の日ボタンクリック時のイベント
    // document.getElementById("next_btn").onclick = function() {
    //     cnt++;
    //     showMonthDate();
    // }
</script>