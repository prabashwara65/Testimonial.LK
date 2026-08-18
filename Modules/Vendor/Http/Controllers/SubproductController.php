<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\SubproductRequest;

use App\Models\Product;

use Modules\Vendor\Http\Services\SubproductService;

use App\Http\Constants\Actions;


class SubproductController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Subproducts";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Product Code@no-sort@' => '',
        'Product Name@no-sort@' => '',
        'Subproduct Code' => 'subproducts.subproduct_code',
        'Subproduct Name' => 'subproducts.subproduct_name',
        'Description' => 'subproducts.description',
        'Status' => 'subproducts.status',
        'Actions@no-sort@' => 'id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(SubproductService $subproductService)
    {
        $this->subproductService = $subproductService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_SUBPRODUCTS, 'vendor');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_SUBPRODUCTS, 'vendor');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_SUBPRODUCTS, 'vendor');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['export_route'] = "subproducts/export";

        $data['scripts'] = [];
        $data['addRoute'] = 'vendor.subproducts.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.subproducts.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_SUBPRODUCTS, 'vendor');

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

            $data = $this->subproductService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

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
        $this->checkPermissionRedirect(Actions::CREATE_SUBPRODUCTS, 'vendor');

        $data['products'] = Product::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();

        $view = View::make('vendor::subproducts.create',$data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubproductRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_SUBPRODUCTS, 'vendor');

            $result = $this->subproductService->createSubproduct($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_SUBPRODUCTS, $result['subproduct']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Subproduct created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        $this->checkPermissionRedirect(Actions::EDIT_SUBPRODUCTS, 'vendor');

        $data['subproduct'] = $this->subproductService->getSubproduct($id);
        $data['products'] = Product::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();

        $view = View::make('vendor::subproducts.edit', $data)->render();
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
    public function update(SubproductRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_SUBPRODUCTS, 'vendor');

            $result = $this->subproductService->updateSubproduct($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_SUBPRODUCTS, $result['subproduct']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Subproduct updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::DELETE_SUBPRODUCTS, 'vendor');

            $result = $this->subproductService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_SUBPRODUCTS, $id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Subproduct deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::EDIT_SUBPRODUCTS, 'vendor');

            $result = $this->subproductService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_SUBPRODUCTS, $result['subproduct']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Subproduct status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

            return $this->subproductService->export($type, $columns, $searchValue);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
