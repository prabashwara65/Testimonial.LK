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

        {{--Filter Buttons--}}
        <div class="row">
            <div class="col-12">
                <div class="filter-btn-container d-sm-flex">
                    @if(isset($export_route) && $export_route)
                        <div class="d-flex mt-2 mt-sm-0">
                            <a onclick="exportData('CSV')" target="_blank" class="filter-btn" tabindex="0" aria-controls="data-tables">
                                <span><i class="icon-file-empty position-left"></i> CSV</span>
                            </a>
                            <a onclick="exportData('EXCEL')" target="_blank" class="filter-btn" tabindex="0" aria-controls="data-tables">
                                <span><i class="icon-file-excel position-left"></i> Excel</span>
                            </a>
                            <a onclick="exportData('PDF')" target="_blank" class="filter-btn" tabindex="0" aria-controls="data-tables">
                                <span><i class="icon-file-pdf position-left"></i> PDF</span>
                            </a>
                            <a onclick="exportData('PRINT')" target="_blank" class="filter-btn" tabindex="0" aria-controls="data-tables">
                                <span><i class="icon-printer position-left"></i> Print</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item"><a class="nav-link @if ($status == 'approved') active @endif" href="{{ Request::is('vendor/admin/testimonials*') ? route('vendor.testimonials.approved') : route('vendor.feedbacks.approved') }}">Approved</a></li>
                        <li class="nav-item"><a class="nav-link @if ($status == 'reject') active @endif" href="{{ Request::is('vendor/admin/testimonials*') ? route('vendor.testimonials.reject') : route('vendor.feedbacks.reject') }}">Reject</a></li>
                        <li class="nav-item"><a class="nav-link @if ($status == 'pending') active @endif" href="{{ Request::is('vendor/admin/testimonials*') ? route('vendor.testimonials.pending') : route('vendor.feedbacks.pending') }}">Pending</a></li>
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

@section('script')
    @if(isset($export_route) && $export_route)
    <script>
        function exportData(type) {
            var searchValue = $('.dataTables_filter input').val();
            var exportWindow = window.open("export", "_blank")
            exportWindow.location.href = "{{$export_route}}/" + type + "/" + searchValue + "?" + $('.filter-form').serialize();
        }
    </script>
    @endif
@endsection