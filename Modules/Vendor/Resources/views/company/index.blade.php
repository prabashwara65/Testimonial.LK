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
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><strong>Company Name :</strong></label>
                                <label class="col-sm-8 col-form-label">{{ $company->company_name }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><strong>Company Address :</strong></label>
                                <label class="col-sm-8 col-form-label">{{ $company->address }}, {{ $company->address_line1 }}, {{ $company->address_line2 }}, {{ $company->country->country }}, {{ $company->region->region }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><strong>BR Number :</strong></label>
                                <label class="col-sm-8 col-form-label">{{ $company->br_no }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><strong>Direct Contact :</strong></label>
                                <label class="col-sm-8 col-form-label">{{ $company->contact_no }}</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><strong>Email :</strong></label>
                                <label class="col-sm-8 col-form-label">{{ $company->email }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img style="max-width: 250px; width: 100%;" src="{{asset('storage/'. $company->logo)}}">
                        </div>
                    </div>
                    @if(isset($editPermission) && $editPermission)
                        <hr>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end">
                                <form class="add-new-form" action="{{ route($editRoute, auth()->user()->vendor_company_id) }}" method="get">
                                    <button class="add-new-button btn">Edit</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
