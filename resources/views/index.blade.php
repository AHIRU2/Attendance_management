@extends('layouts.app')

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">

<div class="dakoku">
    <div class="user-title">
        <?php $user = Auth::user(); ?>{{ $user->name }}さん、お疲れ様です！
    </div>
</div>

<div class="button-form">
    <ul class="btn-list">
        <li class="timebtn" id="btn_punchin">
            <form action="{{route('/attendance/start')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_punchin" class="btn">勤務開始</button>
            </form>
        </li>
        <li class="timebtn" id="btn_punchout">
            <form action="{{route('/attendance/end')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_punchout" class="btn" disabled>勤務終了</button>
            </form>
        </li>
        <li class="timebtn" id="btn_rest_punchin">
            <form action="{{route('/attendance/reststart')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchin" class="btn" disabled>休憩開始</button>
            </form>
        </li>
        <li class="timebtn" id="btn_rest_punchout">
            <form action="{{route('/attendance/restend')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchout" class="btn" disabled>休憩終了</button>
            </form>
        </li>
    </ul>
    <script>
        // function setPunchinButton() {
        //     let setbuttonPunchinOff = $('[id^="btn_punchin"]');
        //     for (i = 0; i < setbuttonPunchinOff.length; i++) {
        //         $('#' + setbuttonPunchinOff[i].id).prop("disabled", true);
        //     }
        //     let setbuttonPunchOutOff = $('[id^="btn_punchout"]');
        //     for (i = 0; i < setbuttonPunchOutOff.length; i++) {
        //         $('#' + setbuttonPunchOutOff[i].id).prop("disabled", false);
        //     }
        // }

        // function setPunchOutButton() {
        //     let setbuttonPunchOutOff = $('[id^="btn_punchout"]');
        //     for (i = 0; i < setbuttonPunchOutOff.length; i++) {
        //         $('#' + setbuttonPunchOutOff[i].id).prop("disabled", true);
        //     }
        //     let setbuttonPunchinOff = $('[id^="btn_punchin"]');
        //     for (i = 0; i < setbuttonPunchinOff.length; i++) {
        //         $('#' + setbuttonPunchinOff[i].id).prop("disabled", false);
        //     }
        // }

        const startWorking = document.getElementById('btn_punchin');
        const endWorking = document.getElementById('btn_punchout');
        const startBrake = document.getElementById('btn_rest_punchin');
        const endBrake = document.getElementById('btn_rest_punchou');
    </script>
    @endsection