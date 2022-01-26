@extends('layouts.app')

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">
<script>
    window.onload = function clickBtn1() {
        var startFlg = JSON.parse('<?php echo $startFlg_json; ?>');
        var endFlg = JSON.parse('<?php echo $endFlg_json; ?>');
        var startRestFlg = JSON.parse('<?php echo $startRestFlg_json; ?>');
        var endRestFlg = JSON.parse('<?php echo $endRestFlg_json; ?>');

        console.log('値の引き渡し確認_startRestFlg:')
        console.log(startRestFlg);

        if (startFlg == true) {
            document.getElementById("btn_punchin").removeAttribute("disabled");
            document.getElementById("btn_punchin").style.color = "black";
        } else {
            // document.getElementById("btn_punchin").setAttribute("disabled", true);
            document.getElementById("btn_punchin").disabled = true;
            document.getElementById("btn_punchin").style.color = "gray";
        }

        if (endFlg == true) {
            document.getElementById("btn_punchout").removeAttribute("disabled");
            document.getElementById("btn_punchout").style.color = "black";
        } else {
            document.getElementById("btn_punchout").setAttribute("disabled", true);
            document.getElementById("btn_punchout").style.color = "gray";
        }

        if (startRestFlg == true) {
            document.getElementById("btn_rest_punchin").removeAttribute("disabled");
            document.getElementById("btn_rest_punchin").style.color = "black";
        } else {
            document.getElementById("btn_rest_punchin").setAttribute("disabled", true);
            document.getElementById("btn_rest_punchin").style.color = "gray";
        }

        if (endRestFlg == true) {
            document.getElementById("btn_rest_punchout").removeAttribute("disabled");
            document.getElementById("btn_rest_punchout").style.color = "black";
        } else {
            document.getElementById("btn_rest_punchout").setAttribute("disabled", true);
            document.getElementById("btn_rest_punchout").style.color = "gray";
        }
    }
</script>

<div class="dakoku">
    <div class="user-title">
        <?php $user = Auth::user(); ?>{{ $user->name }}さん、お疲れ様です！
    </div>
</div>

<div class="button-form">
    <ul class="btn-list">
        <li class="timebtn" id="punchin">
            <form action="{{route('/attendance/start')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_punchin" class="btn">勤務開始</button>
            </form>
        </li>
        <li class="timebtn" id="punchout">
            <form action="{{route('/attendance/end')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_punchout" class="btn">勤務終了</button>
            </form>
        </li>
        <li class="timebtn" id="rest_punchin">
            <form action="{{route('/attendance/reststart')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchin" class="btn">休憩開始</button>
            </form>
        </li>
        <li class="timebtn" id="rest_punchout">
            <form action="{{route('/attendance/restend')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" id="btn_rest_punchout" class="btn">休憩終了</button>
            </form>
        </li>
    </ul>
    @endsection
    <!-- @push('js') -->
    <!-- <script src="{{ asset('js/index.js') }}"> -->