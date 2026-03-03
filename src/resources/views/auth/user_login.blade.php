@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/user_login.css') }}">
<link rel="stylesheet" href="{{ asset('css/validate.css') }}">
@endsection

@section('content')
<form class="login-form" action="/login" method="post">
    <div class="login-form__inner">
        <h1 class="login-form__header">ログイン</h1>
        <span class="login-form__span">メールアドレス</span>
        <input class="login-form__input" type="text">
        <div class="form-error">
            @error('password')
            {{ $message }}
            @enderror
        </div>
        <span class="login-form__span">パスワード</span>
        <input class="login-form__input" type="text">
        <div class="form-error">
            @error('password')
            {{ $message }}
            @enderror
        </div>

        <button class="login-form__button">ログインする</button>
        <a class="link-register" href="/register">会員登録はこちら</a>
    </div>
</form>

@endsection