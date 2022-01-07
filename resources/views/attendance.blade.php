@extends('layouts.app')

@section('content')
<div>{{ $today }}</div>

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