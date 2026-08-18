<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Subproduct;

use Modules\Vendor\Http\Repositories\SubproductRepository;

use App\Http\Constants\Actions;

class SubproductService extends MainService
{
    protected $subproductRepository;

    public function __construct(
        SubproductRepository $subproductRepository)
    {
        $this->subproductRepository = $subproductRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $subproduct = Subproduct::with('product')
            ->whereHas('product', function ($q) {
                $q->where('vendor_company_id', auth()->user()->vendor_company_id);
            });
        // * customize end

        $columns = [
            'Subproduct ID' => 'subproduct_code',
            'Subproduct Name' => 'subproduct_name',
            'Description@no-sort@' => 'description',
        ];

        if (!empty($search)) {
            $subproduct = $this->search($subproduct, $columns, $search);
        }
        if (!empty($orderBy)) {
            $subproduct->orderBy($orderBy, $orderDirection);
        } else {
            $subproduct->orderBy('subproducts.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $subproduct->get();
        $count = count($rows);

        // limit the results for pagination
        $subproduct->offset($offset)->limit($limit);
        $rows = $subproduct->get();

        $data = [];

        foreach ($rows as $subproduct) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$subproduct->id.'" onchange="changeUserStatus(event.target, '. $subproduct->id .', \''. route('vendor.subproducts.status') .'\')" ';
            $toggle .= ($subproduct->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$subproduct->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $subproduct->product->product_code,
                $subproduct->product->product_name,
                $subproduct->subproduct_code,
                $subproduct->subproduct_name,
                $subproduct->description,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.subproducts', $subproduct->id, ['view' => false, 'edit' => Actions::EDIT_SUBPRODUCTS, 'delete' => Actions::DELETE_SUBPRODUCTS])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Subproduct::count(); // count of all the records in the database table

        return $out;
    }

    public function createSubproduct($request)
    {
        try {
            $input = $request->only(['product_id', 'subproduct_code', 'subproduct_name', 'description']);
            $subproduct = $this->subproductRepository->create($input);

            return ['status' => 'success', 'subproduct' => $subproduct];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateSubproduct($request, $id)
    {
        try {
            $subproduct = $this->subproductRepository->show($id); //Get subproduct specified by id

            $input = $request->only(['product_id', 'subproduct_code', 'subproduct_name', 'description']);
            $subproduct->fill($input)->save();

            return ['status' => 'success', 'subproduct' => $subproduct];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getSubproduct($editId)
    {
        $subproduct = $this->subproductRepository->show($editId);

        $temp['id'] = $subproduct->id;
        $temp['product_id'] = $subproduct->product_id;
        $temp['subproduct_code'] = $subproduct->subproduct_code;
        $temp['subproduct_name'] = $subproduct->subproduct_name;
        $temp['description'] = $subproduct->description;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $this->subproductRepository->delete($id);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStatus($request)
    {
        try {
            $subproduct = $this->subproductRepository->show($request->id); //Get subproduct specified by id

            $input = $request->only(['status']);
            $subproduct->fill($input)->save();

            return ['status' => 'success', 'subproduct' => $subproduct];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Subproducts';

            $columnHeaders = array('Product Code', 'Product Name', 'Subproduct ID', 'Subproduct Name', 'Description', 'Status');
            $tableColumns = array('product_code', 'product_name', 'subproduct_code', 'subproduct_name', 'description', 'status');

            $model = Subproduct::with('product')
                    ->whereHas('product', function ($q) {
                        $q->where('products.vendor_company_id', auth()->user()->vendor_company_id);
                    })
                    ->join('products', 'subproducts.product_id', 'products.id');

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = array('product_code', 'product_name', 'subproduct_code', 'subproduct_name', 'description');
            array_push( $select,
                        DB::raw("(CASE WHEN subproducts.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
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

            $query->orWhereHas('product', function ($q) use ($search){
                $q->where('products.product_code', 'LIKE', '%'.$search.'%');
                $q->orWhere('products.product_name', 'LIKE', '%'.$search.'%');
            });
        });

        return $model;
    }

}