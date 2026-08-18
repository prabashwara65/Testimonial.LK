@extends('layouts.frontend')

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
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                @error('email')
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror

                                <div class="form-row">
                                    <div class="col-xs-12">
                                        <label>{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-xs-12 d-flex justify-center mt-20 pt-20">
                                        <div class="submit-holder">
                                            <button type="submit">{{ __('Send Password Reset Link') }}</button>
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
