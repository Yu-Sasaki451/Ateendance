@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <div class="verify-email__card">
        <p class="verify-email__text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        @if (session('status') === 'verification-link-sent')
            <p class="verify-email__message">
                認証メールを再送信しました。
            </p>
        @endif

        <a class="verify-email__mailhog-link" href="http://localhost:8025" target="_blank" rel="noopener noreferrer">
            認証はこちらから
        </a>

        <form class="verify-email__form" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="verify-email__button" type="submit">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection
