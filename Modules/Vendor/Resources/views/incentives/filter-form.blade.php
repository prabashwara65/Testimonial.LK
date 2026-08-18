<div class="row">
    <div class="col-md-12 mb-3">
        <form action="" class="filter-form filter-form-secondary">
            <div class="form-group row">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="start_date" class="col-md-4 col-form-label">Start Date</label>
                        <div class="col-md-8">
                            <input type="text" name="form[start_date]" id="start_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d", strtotime("-30 day"))}}">
                            <span class="invalid-feedback start_date-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="end_date" class="col-md-4 col-form-label">End Date</label>
                        <div class="col-md-8">
                            <input type="text" name="form[end_date]" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}">
                            <span class="invalid-feedback end_date-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" class="filter-form-submit btn btn-primary"><i class="fa fa-line-chart"></i> Show</button>
                </div>
            </div>
        </form>
    </div>
</div>
