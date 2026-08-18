<div class="row">
    <div class="col-md-12 mb-3">
        <form action="" class="filter-form filter-form-secondary">
            <div class="form-group row">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="customer" class="col-md-4 col-form-label">Customer name</label>
                        <div class="col-md-8">
                            <input type="text" name="customer" id="customer" class="form-control">
                            <span class="invalid-feedback customer-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="employee" class="col-md-4 col-form-label">Employee name</label>
                        <div class="col-md-8">
                            <input type="text" name="employee" id="employee" class="form-control">
                            <span class="invalid-feedback employee-error" role="alert"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="campaign_id" class="col-md-4 col-form-label">Campaign</label>
                        <div class="col-md-8">
                            <select name="campaign_id" id="campaign_id" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{$campaign->id}}">{{$campaign->campaign_name}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback campaign_id-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="response_type" class="col-md-4 col-form-label">Questionnaire / Record</label>
                        <div class="col-md-8">
                            <select name="response_type" id="response_type" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                <option value="Questionnaire">Questionnaire</option>
                                <option value="Record">Record</option>
                            </select>
                            <span class="invalid-feedback response_type-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="product_id" class="col-md-4 col-form-label">Product name</label>
                        <div class="col-md-8">
                            <select name="product_id" id="product_id" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->product_name}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback product_id-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="subproduct_id" class="col-md-4 col-form-label">Subproduct name</label>
                        <div class="col-md-8">
                            <select name="subproduct_id" id="subproduct_id" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                @foreach($subproducts as $subproduct)
                                    <option value="{{$subproduct->id}}">{{$subproduct->subproduct_name}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback subproduct_id-error" role="alert"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="input_source" class="col-md-4 col-form-label">Input source</label>
                        <div class="col-md-8">
                            <select name="input_source" id="input_source" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                <option value="Web">Web</option>
                                <option value="App">App</option>
                            </select>
                            <span class="invalid-feedback input_source-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="start_date" class="col-md-4 col-form-label">Start Date</label>
                        <div class="col-md-8">
                            <input type="text" name="start_date" id="start_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d", strtotime("-30 day"))}}">
                            <span class="invalid-feedback start_date-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="end_date" class="col-md-4 col-form-label">End Date</label>
                        <div class="col-md-8">
                            <input type="text" name="end_date" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}">
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
