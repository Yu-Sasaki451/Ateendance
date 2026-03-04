@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">
@endsection

@section('header-menu')
<nav class="header-nav">
    <a class="nav-link" href="/attendance">勤怠</a>
    <a class="nav-link" href="/attendance/list">勤怠一覧</a>
    <a class="nav-link" href="/stamp_correction_request/list">申請</a>
    <form  action="/logout" method="post">
    @csrf
    <input type="hidden" name="logout_from" value="user">
    <button class="nav-button" type="submit">ログアウト</button>
    </form>
</nav>
@endsection

@section('content')

<div class="attendance">
    <p class="attendance-status">{{ $status }}</p>
    <p class="info-today">{{ $today }}（{{ $weekday }}）</p>
    <p class="info-now">{{ $currentTime }}</p>

    @if ($message)
        <p class="attendance-message">{{ $message }}</p>
    @endif
    <div class="attendance-buttons">
        @foreach ($buttons as $button)
            <form action="{{ $button['route'] }}" method="post">
                @csrf
                <button class="attendance-button attendance-button--{{ $button['type'] }}" type="submit">{{ $button['label'] }}</button>
            </form>
        @endforeach
    </div>
</div>

@endsection