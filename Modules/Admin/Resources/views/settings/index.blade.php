@extends('layouts.backend')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="c-grey-900 m-0">{{$title}}</h4>
                    </div>

                    <div class="header-elements">
                        <div class="d-flex">
                            <div class="breadcrumb">
                                <a href="{{ route('vendor.dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                                    Dashboard</a>
                                <a class="breadcrumb-item active">{{$title}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    {{ Form::open(array('url' => route('admin.settings.update', 1), 'class'=>'text-form', 'files' => 'true', 'method' => 'put', 'onsubmit' => 'return false;')) }}
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($settings as $setting)
                                @empty(!$setting->value)
                                    <div class="form-group row">
                                        <label for="{{ $setting->name }}" class="col-sm-3 col-form-label">{{ $setting->name }} <i class="required-star"></i></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="{{ $setting->name }}" name="{{ $setting->name }}" value="{{ $setting->value }}">
                                        </div>
                                    </div>
                                @endempty
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection