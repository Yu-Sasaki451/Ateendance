<form class="auth-form" action="/login" method="post">
    @csrf
    @if(!empty($loginType))
        <input type="hidden" name="login_type" value="{{ $loginType }}">
    @endif

    <div class="auth-form__inner">
        <h1 class="auth-form__header">ログイン</h1>
        <span class="auth-form__label">メールアドレス</span>
        <input class="auth-form__input" type="text" name="email" value="{{ old('email') }}">
        <div class="form-error">
            @error('email')
            {{ $message }}
            @enderror
        </div>
        <span class="auth-form__label">パスワード</span>
        <input class="auth-form__input" type="password" name="password">
        <div class="form-error">
            @error('password')
            {{ $message }}
            @enderror
        </div>

        <button class="auth-form__button">{{ $submitLabel }}</button>

        @isset($subLinkUrl)
            <a class="auth-form__link" href="{{ $subLinkUrl }}">{{ $subLinkLabel }}</a>
        @endisset
    </div>
</form>
