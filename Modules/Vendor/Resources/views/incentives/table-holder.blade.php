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
                        <li class="nav-item"><a class="nav-link @if ($status == 'paid') active @endif" href="{{ route('vendor.incentives.paid') }}">Paid</a></li>
                        <li class="nav-item"><a class="nav-link @if ($status == 'reject') active @endif" href="{{ route('vendor.incentives.reject') }}">Reject</a></li>
                        <li class="nav-item"><a class="nav-link @if ($status == 'pending') active @endif" href="{{ route('vendor.incentives.pending') }}">Pending</a></li>
                    </ul>
                </div>
                <div class="bgc-white bd bdrs-3 p-20 mB-20" id="table-holder">
                    @include('datatables.table')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('datatables.script')

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
