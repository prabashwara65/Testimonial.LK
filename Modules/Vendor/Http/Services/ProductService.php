<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Product;

use Modules\Vendor\Http\Repositories\ProductRepository;

use App\Http\Constants\Actions;

class ProductService extends MainService
{
    protected $productRepository;

    public function __construct(
        ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $product = Product::where('vendor_company_id', auth()->user()->vendor_company_id);
        // * customize end

        if (!empty($search)) {
            $product = $this->search($product, $columns, $search);
        }
        if (!empty($orderBy)) {
            $product->orderBy($orderBy, $orderDirection);
        } else {
            $product->orderBy('products.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $product->get();
        $count = count($rows);

        // limit the results for pagination
        $product->offset($offset)->limit($limit);
        $rows = $product->get();

        $data = [];

        foreach ($rows as $product) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$product->id.'" onchange="changeUserStatus(event.target, '. $product->id .', \''. route('vendor.products.status') .'\')" ';
            $toggle .= ($product->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$product->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $product->product_code,
                $product->product_name,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.products', $product->id, ['view' => false, 'edit' => Actions::EDIT_PRODUCTS, 'delete' => Actions::DELETE_PRODUCTS])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Product::count(); // count of all the records in the database table

        return $out;
    }

    public function createProduct($request)
    {
        try {
            $input = $request->only(['product_code', 'product_name']);
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;
            
            $product = $this->productRepository->create($input);

            return ['status' => 'success', 'product' => $product];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateProduct($request, $id)
    {
        try {
            $product = $this->productRepository->show($id); //Get product specified by id

            $input = $request->only(['product_code', 'product_name']);
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;
            $product->fill($input)->save();

            return ['status' => 'success', 'product' => $product];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getProduct($editId)
    {
        $product = $this->productRepository->show($editId);

        $temp['id'] = $product->id;
        $temp['product_code'] = $product->product_code;
        $temp['product_name'] = $product->product_name;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $this->productRepository->delete($id);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStatus($request)
    {
        try {
            $product = $this->productRepository->show($request->id); //Get product specified by id

            $input = $request->only(['status']);
            $product->fill($input)->save();

            return ['status' => 'success', 'product' => $product];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Products';

            $columnHeaders = array('Product Code', 'Product Name', 'Status');
            $tableColumns = array('product_code', 'product_name', 'status');

            $model = Product::where('vendor_company_id', auth()->user()->vendor_company_id);

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = $tableColumns;
            array_push( $select,
                        DB::raw("(CASE WHEN products.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
                    );
            $model = $model->get($select);

            return $this->exportReport($type, $searchValue, $title, $columnHeaders, $tableColumns, $model);
    
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function search($model, $columns, $search)
    {
        $model->where(function($query) use ($columns, $search){
            $query = $this->generateWhereLikeQuery($query, $columns, $search);
        });

        return $model;
    }

}