@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="register-form">
                            <div class="register-label">
                                <!-- <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label> -->

                                <div class="register-input">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="名前" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="register-label">
                                <!-- <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label> -->

                                <div class="register-input">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="メールアドレス" 　required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="register-label">
                                <!-- <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label> -->

                                <div class="register-input">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="パスワード" 　required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="register-label">
                                <!-- <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label> -->

                                <div class="register-input">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="確認用パスワード" 　required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="register-label">
                                <div class="register-input">
                                    <button type="submit" class="register-button">
                                        会員登録
                                    </button>
                                </div>
                            </div>

                            <div class="link-login-label">
                                <div class="link-login">
                                    アカウントをお持ちの方はこちら
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection