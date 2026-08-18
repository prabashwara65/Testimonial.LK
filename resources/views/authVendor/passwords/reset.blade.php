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
                                    <a class="link-to text-white">{{ __('Reset Password') }}</a>
                                </div>
                            </div>
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
                            <form method="POST" action="{{ route('vendor.password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-row">
                                    <div class="col-xs-12">
                                        <label>{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="col-xs-12">
                                        <label>{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-xs-12">
                                        <label>{{ __('Confirm Password') }}</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-xs-12 d-flex justify-center mt-20 pt-20">
                                        <div class="submit-holder">
                                            <button type="submit">{{ __('Reset Password') }}</button>
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
@endsection