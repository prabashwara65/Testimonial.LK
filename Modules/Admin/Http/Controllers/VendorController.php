<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\VendorRequest;

use App\Models\VendorCompany;
use App\Models\Region;

use Modules\Admin\Http\Services\VendorService;

use App\Http\Constants\Actions;


class VendorController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Vendor Users";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'EMP ID' => 'vendors.emp_id',
        'Name' => 'vendors.name',
        'NIC' => 'vendors.nic',
        'Email' => 'vendors.email',
        'Mobile' => 'vendors.mobile',
        'Address' => 'vendors.address',
        'Country@no-sort@' => '',
        'Region@no-sort@' => '',
        'Designation' => 'vendors.designation',
        'Department' => 'vendors.department',
        'Vendor Company@no-sort@' => '',
        'Status@no-sort@' => 'vendors.status',
        'Actions@no-sort@' => 'vendors.id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_VENDORS, 'admin');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_VENDORS, 'admin');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_VENDORS, 'admin');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['scripts'] = ['user_management.js'];
        $data['addRoute'] = 'admin.vendors.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'admin.vendors.get-data', 'holder' => 'table-holder'];

        return view('common.table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_VENDORS, 'admin');

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

            $data = $this->vendorService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

            $data['draw'] = $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->checkPermissionRedirect(Actions::CREATE_VENDORS, 'admin');

        $data['vendor_companies'] = VendorCompany::where('status', 1)->get();
        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::vendor_users.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     * @param VendorRequest $request
     * @return Renderable
     */
    public function store(VendorRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_VENDORS, 'admin');

            $result = $this->vendorService->createVendor($request);

            if ($result['status'] == 'success') {
                // remove token and password from logged request parameters
                $this->logAction(Actions::CREATE_VENDORS, $result['vendor']->id, json_encode(request()->except(['password', 'password_confirmation'])), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Vendor created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_VENDORS, 'admin');

        $data['vendor'] = $this->vendorService->getVendor($id); //Get vendor with specified id
        $data['vendor_companies'] = VendorCompany::where('status', 1)->get();
        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::vendor_users.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(VendorRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_VENDORS, 'admin');

            $result = $this->vendorService->updateVendor($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_VENDORS, $result['vendor']->id, json_encode(request()->except(['password', 'password_confirmation'])), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Vendor updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        //
    }

    public function changeStatus(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_VENDORS, 'admin');

            $result = $this->vendorService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_VENDORS, $result['vendor']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Vendor status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
}
