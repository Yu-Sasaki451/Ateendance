@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
<link rel="stylesheet" href="{{ asset('css/validation.css') }}">
@endsection

@section('content')
@include('partials.auth.login_form', [
    'loginType' => 'admin',
    'submitLabel' => '管理者ログインする',
])
@endsection
