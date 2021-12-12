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
                <button type="submit" class="btn_puchin">出勤</button>
            </form>
        </li>
        <li>
            <form action="{{route('/attendance/end')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" class="btn_puchout">退勤</button>
            </form>
        </li>
        <li>
            <form action="{{route('/attendance/reststart')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" class="btn_rest_puchin">休憩開始</button>
            </form>
        </li>
        <li>
            <form action="{{route('/attendance/restend')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" class="btn_rest_puchout">休憩終了</button>
            </form>
        </li>
    </ul>

    @endsection