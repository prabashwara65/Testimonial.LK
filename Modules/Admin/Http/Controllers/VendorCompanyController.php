<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\VendorCompanyRequest;

use App\Models\Region;

use Modules\Admin\Http\Services\VendorCompanyService;

use App\Http\Constants\Actions;


class VendorCompanyController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Vendor Companies";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'BR NO' => 'vendor_companies.br_no',
        'Company Name' => 'vendor_companies.company_name',
        'Email' => 'vendor_companies.email',
        'Contact No' => 'vendor_companies.contact_no',
        'Company Address' => 'vendor_companies.address',
        'Country@no-sort@' => '',
        'Region@no-sort@' => '',
        'Status@no-sort@' => '',
        'Actions@no-sort@' => 'vendor_companies.id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    function __construct(VendorCompanyService $vendorCompanyService)
    {
        $this->vendorCompanyService = $vendorCompanyService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_VENDOR_COMPANIES, 'admin');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_VENDOR_COMPANIES, 'admin');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_VENDOR_COMPANIES, 'admin');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['scripts'] = ['user_management.js'];
        $data['addRoute'] = 'admin.vendor-companies.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'admin.vendor-companies.get-data', 'holder' => 'table-holder'];

        return view('common.table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_VENDOR_COMPANIES, 'admin');

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

            $data = $this->vendorCompanyService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

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
        $this->checkPermissionRedirect(Actions::CREATE_VENDOR_COMPANIES, 'admin');

        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::vendor_companies.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     * @param VendorCompanyRequest $request
     * @return Renderable
     */
    public function store(VendorCompanyRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_VENDOR_COMPANIES, 'admin');

            $result = $this->vendorCompanyService->createVendorCompany($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_VENDOR_COMPANIES, $result['vendorCompany']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Vendor company created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_VENDOR_COMPANIES, 'admin');

        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $data['vendorCompany'] = $this->vendorCompanyService->getVendorCompany($id); //Get vendor company with specified id

        $view = View::make('admin::vendor_companies.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     * @param VendorCompanyRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(VendorCompanyRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_VENDOR_COMPANIES, 'admin');

            $result = $this->vendorCompanyService->updateVendorCompany($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_VENDOR_COMPANIES, $result['vendorCompany']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Vendor company updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::EDIT_VENDOR_COMPANIES, 'admin');

            $result = $this->vendorCompanyService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_VENDOR_COMPANIES, $result['vendorCompany']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Vendor company status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
