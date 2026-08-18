{{ Form::open(array('url' => route('vendor.products.store'), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Product</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="product_code" class="col-sm-4 col-form-label">Product ID<i class="required-star"></i></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="product_code" name="product_code" >
                <span class="invalid-feedback product_code-error" role="alert"></span>
            </div>
        </div>
    </div>
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="product_name" class="col-sm-4 col-form-label">Product Name<i class="required-star"></i></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="product_name" name="product_name" >
                <span class="invalid-feedback product_name-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}