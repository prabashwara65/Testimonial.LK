<div class="row">
    <div class="col-md-12 mb-3">
        <form action="" class="filter-form filter-form-secondary">
            <div class="form-group row">
                <div class="col-sm-6 col-md-4 col-lg-4">
                    <div class="row">
                        <label for="vendor_company_id" class="col-md-4 col-form-label">Vendor Company</label>
                        <div class="col-md-8">
                            <select name="vendor_company_id" id="vendor_company_id" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                @foreach($vendorCompanies as $company)
                                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback vendor_company_id-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <button type="button" class="filter-form-submit btn btn-primary"><i class="fa fa-line-chart"></i> Show</button>
                </div>
            </div>
        </form>
    </div>
</div>
