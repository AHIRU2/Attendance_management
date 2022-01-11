@extends('layouts.app')

@section('content')
<div>
    <input type="submit" name="" value="<<" id="back_btn">
    <div id="date_txt">{{ $today }}</div>
    <input type="submit" name="" value=">>" id="next_btn">
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
        <td>{{ $item->start_time}}</td>
        <td>{{ $item->end_time }}</td>
        <td>{{ $item->rest_time }}</td>
        <td>勤務時間</td>
    </tr>
    @endforeach
</table>
{{ $items->links()}}
@endsection
<script>
    var cnt = 0;

    function showMonthDate() {
        var nowDate = new Date();
        var myDate = new Date(nowDate.getTime() + 86400000 * cnt);
        var mm = ("0" + (myDate.getMonth() + 1)).slice(-2);
        var dd = ("0" + (myDate.getDate())).slice(-2);
        document.getElementById("date_txt").value = mm + "月" + dd + "日";
    }

    //関数 showMonthDateを即実行
    showMonthDate();

    //前日ボタンクリック時のイベント
    document.getElementById("back_btn").onclick = function() {
        cnt--;
        showMonthDate();
    }

    //次の日ボタンクリック時のイベント
    document.getElementById("next_btn").onclick = function() {
        cnt++;
        showMonthDate();
    }
</script>