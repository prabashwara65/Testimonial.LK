<div id="campaign{{$count}}">
    <div class="row mt-2">
        <div class='col-lg-12'>
            <div class="form-group row">

                <div class="col-sm-6">
                    <select name="product_id[{{$count}}]" id="product_id[{{$count}}]" class="form-control load-data-on-change"
                            data-url="{{$loadSubproductUrl}}" data-target="#subproduct_id{{$count}}" data-live-search="true">
                        <option>Select a product</option>
                        @foreach ($products as $product)
                            <option value="{{$product['id']}}"
                                @if(isset($productId) && $productId == $product['id'])
                                    selected
                                    <?php
                                        $allsubproducts = \App\Models\Subproduct::where('product_id', '=', $productId)->where('status', 1)->get();
                                    ?>
                                @endif>{{$product['product_code']}} - {{$product['product_name']}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback product_id.{{$count}}-error" role="alert"></span>
                </div>

                <div class="col-sm-6">
                    <select name="subproduct_id[{{$count}}][]" id="subproduct_id{{$count}}" class="form-control on-load" multiple data-live-search="true">
                        @if(isset($productId))
                            @foreach ($allsubproducts as $subproduct)
                                <option value="{{$subproduct->id}}" @if(in_array($subproduct['id'], $subproducts)) selected @endif >{{ $subproduct->subproduct_name }}</option>
                            @endforeach
                        @endif
                    </select>

                    <span class="invalid-feedback subproduct_id.{{$count}}-error" role="alert"></span>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 offset-md-9">
            <p class="text-right">
                <button type="button" data-count="{{$count}}" class="remove-question-btn btn btn-sm btn-outline-danger pull-right"><i class="fa fa-minus"></i> Remove Product</button>
            </p>
        </div>
    </div>
    <hr>
</div>
