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
        <div class="filter-form-holder">
            @if(!empty($table_before_section))
                {!! $table_before_section !!}
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item"><a class="nav-link @if ($status == 'paid') active @endif" href="{{ route('admin.payment-renewals.paid') }}">Paid</a></li>
                        <li class="nav-item"><a class="nav-link @if ($status == 'pending') active @endif" href="{{ route('admin.payment-renewals.pending') }}">Pending</a></li>
                    </ul>
                </div>
                <div class="bgc-white bd bdrs-3 p-20 mB-20" id="table-holder">
                    <table id="custom-dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%" >
                        <thead>
                        <tr>
                            @if(!empty($columns))
                                @foreach ($columns as $columnName => $value)
                                    <th class="@if(strpos($columnName, '@no-sort@')) no-sort @endif">{{str_replace('@no-sort@', '', $columnName)}}</th>
                                @endforeach
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection