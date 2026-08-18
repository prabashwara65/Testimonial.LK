@extends('layouts.backend')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <h4 class="c-grey-900 mT-10 mB-30">{{$title}}</h4>
            </div>
        </div>
        <div class="filter-form-holder">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20" id="table-holder">
                </div>
            </div>
        </div>
    </div>
@endsection