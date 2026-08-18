{{ Form::open(array('url' => route('vendor.subproducts.store'), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Subproduct</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="col-lg-8 offset-lg-2">
        <div class="form-group row">
            <label for="product_id" class="col-sm-4 col-form-label">Product <i
                    class="required-star"></i></label>
            <div class="col-sm-8">
                <select name="product_id" id="product_id" class="form-control" data-live-search="true">
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_code }} - {{ $product->product_name }}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback role-error" role="alert"></span>
            </div>
        </div>
    </div>
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="subproduct_code" class="col-sm-4 col-form-label">Subproduct ID<i class="required-star"></i></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="subproduct_code" name="subproduct_code" >
                <span class="invalid-feedback subproduct_code-error" role="alert"></span>
            </div>
        </div>
    </div>
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="subproduct_name" class="col-sm-4 col-form-label">Subproduct Name<i class="required-star"></i></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="subproduct_name" name="subproduct_name" >
                <span class="invalid-feedback subproduct_name-error" role="alert"></span>
            </div>
        </div>
    </div>
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="description" class="col-sm-4 col-form-label">Description </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="description" name="description" >
                <span class="invalid-feedback description-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}