@extends('layouts.backend')

@section('title', '| Roles')

@section('content')

    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1">
        <p class="text-center mt-5">
            <img src="{{ asset('assets/static/images/cancel.svg') }}" style="width: 60px; text-align: center" alt="">
        <h2 class="text-center">Sorry! <br> <span style="font-weight: 300;">You do not seem to have permission to access this page</span></h2>
        </p>
    </div>

@endsection