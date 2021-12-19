@extends('layouts.app')

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">

<div class="dakoku">
    <div class="user-title">
        <?php $user = Auth::user(); ?>{{ $user->name }}さん、お疲れ様です！
    </div>
</div>

<div class="button-form">
    <ul>
        <li>
            <form action="{{route('/attendance/start')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_punchin">出勤</button>
            </form>
        </li>
        <li>
            <form action="{{route('/attendance/end')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_punchout">退勤</button>
            </form>
        </li>
        <li>
            <form action="{{route('/attendance/reststart')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchin">休憩開始</button>
            </form>
        </li>
        <li>
            <form action="{{route('/attendance/restend')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchout">休憩終了</button>
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
    </script>
    @endsection