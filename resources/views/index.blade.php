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
                <button type="submit" id="btn_punchout" class="btn">勤務終了</button>
            </form>
        </li>
        <li class="timebtn" id="btn_rest_punchin">
            <form action="{{route('/attendance/reststart')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchin" class="btn">休憩開始</button>
            </form>
        </li>
        <li class="timebtn" id="btn_rest_punchout">
            <form action="{{route('/attendance/restend')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchout" class="btn">休憩終了</button>
            </form>
        </li>
    </ul>
    <script>
        window.onload = function clickBtn1() {
            var startFlg = JSON.parse('<?php echo $startFlg_json; ?>');
            var endFlg = JSON.parse('<?php echo $endFlg_json; ?>');
            var startRestFlg = JSON.parse('<?php echo $startRestFlg_json; ?>');
            var endRestFlg = JSON.parse('<?php echo $endRestFlg_json; ?>');

            console.log('値の引き渡し確認_endFlg:')
            console.log(endFlg);

            if (startFlg == true) {
                document.getElementsById("btn_punchin").removeAttribute("disabled");
                document.getElementsById("btn_punchin").style.color = "black";
            } else {
                document.getElementsById("btn_punchin").setAttribute("disabled", true);
                document.getElementsById("btn_punchin").style.color = "white";
            }

            if (endFlg == true) {
                document.getElementsById("btn_punchout").removeAttribute("disabled");
                document.getElementsById("btn_punchout").style.color = "black";
            } else {
                document.getElementsById("btn_punchout").setAttribute("disabled", true);
                document.getElementsById("btn_punchout").style.color = "white";
            }

            if (startRestFlg == true) {
                document.getElementsById("btn_rest_punchin").removeAttribute("disabled");
                document.getElementsById("btn_rest_punchin").style.color = "black";
            } else {
                document.getElementsById("btn_rest_punchin").setAttribute("disabled", true);
                document.getElementsById("btn_rest_punchin").style.color = "white";
            }

            if (endRestFlg == true) {
                document.getElementsById("btn_rest_punchout").removeAttribute("disabled");
                document.getElementsById("btn_rest_punchout").style.color = "black";
            } else {
                document.getElementsById("btn_rest_punchout").setAttribute("disabled", true);
                document.getElementsById("btn_rest_punchout").style.color = "white";
            }
        }
    </script>
    @endsection