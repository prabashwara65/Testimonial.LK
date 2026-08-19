@extends('layouts.vendor')

@section('content')
    <div class="container-fluid main-theme-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="login-register-form">
                        <div class="form-holder">
                            <div class="form-row form-links">
                                <div class="col-xs-12">
                                    <a href="{{ route('vendor.login') }}" class="link-to text-white">Vendor Login</a>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('vendor.login') }}" class="loginForm">
                                @csrf

                                @error('email')
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                                @error('password')
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror

                                <div class="form-row">
                                    <div class="col-xs-12">
                                        <label>Username/Email</label>
                                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-xs-12">
                                        <label>Password</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-xs-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <a href="{{ route('vendor.password.request') }}" class="forget-link">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-xs-12 d-flex justify-center mt-20 pt-20">
                                        <div class="submit-holder">
                                            <button type="submit">
                                                {{ __('Login') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('script')
@endsection
