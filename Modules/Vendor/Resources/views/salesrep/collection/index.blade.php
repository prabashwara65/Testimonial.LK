@extends('layouts.vendor')

@section('content')
    {{-- Dashboard --}}
    <div class="container-fluid second-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row-title">Testimonials and Feedback Collection</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="bgc-white bd p-20 mB-20" id="table-holder">
                        @include('datatables.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('datatables.script')
@endsection
