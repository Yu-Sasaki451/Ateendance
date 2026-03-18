@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
<link rel="stylesheet" href="{{ asset('css/validation.css') }}">
@endsection

@section('content')
@include('partials.auth.login_form', [
    'submitLabel' => 'ログインする',
    'subLinkUrl' => '/register',
    'subLinkLabel' => '会員登録はこちら',
])
@endsection
