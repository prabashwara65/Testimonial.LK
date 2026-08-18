<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Vendor\Http\Exports\ProductExport;

use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\ProductRequest;

use App\Models\Product;

use Modules\Vendor\Http\Services\ProductService;

use App\Http\Constants\Actions;


class ProductController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Products";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Product Code' => 'products.product_code',
        'Product Name' => 'products.product_name',
        'Status' => 'products.status',
        'Actions@no-sort@' => 'id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_PRODUCTS, 'vendor');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_PRODUCTS, 'vendor');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_PRODUCTS, 'vendor');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['export_route'] = "products/export";

        $data['scripts'] = [];
        $data['addRoute'] = 'vendor.products.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.products.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_PRODUCTS, 'vendor');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];
            $columns = $this->columns;

            $orderBy = '';
            $orderDirection = '';
            if (isset($input['order'])) {
                $orderBy = $this->getOrderByColumn($columns, $input['order'][0]['column']);
                $orderDirection = $input['order'][0]['dir'];
            }

            $data = $this->productService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkPermissionRedirect(Actions::CREATE_PRODUCTS, 'vendor');

        $view = View::make('vendor::products.create')->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_PRODUCTS, 'vendor');

            $result = $this->productService->createProduct($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_PRODUCTS, $result['product']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Product created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_PRODUCTS, 'vendor');

        $data['product'] = $this->productService->getProduct($id);

        $view = View::make('vendor::products.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_PRODUCTS, 'vendor');

            $result = $this->productService->updateProduct($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_PRODUCTS, $result['product']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Product updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::DELETE_PRODUCTS, 'vendor');

            $result = $this->productService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_PRODUCTS, $id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Product deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_PRODUCTS, 'vendor');

            $result = $this->productService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_PRODUCTS, $result['product']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Product status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            } else {
                $outPutArray = array('status' => 'error', 'message' => 'Something went wrong', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function export($type = null, $searchValue = null)
    {
        try {
            $columns = $this->columns;

            return $this->productService->export($type, $columns, $searchValue);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
