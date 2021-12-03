@extends('layouts.app')

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">

<div class="dakoku">
    <div class="user-title">
        <?php $user = Auth::user(); ?>{{ $user->name }}さん、お疲れ様です！
    </div>
</div>

@endsection