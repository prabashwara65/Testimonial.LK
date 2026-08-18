<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\BranchRequest;

use App\Models\Region;
use App\Models\Country;
use App\Models\Province;

use Modules\Vendor\Http\Services\BranchService;

use App\Http\Constants\Actions;

class BranchController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Branch Details";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Branch ID' => 'branches.branch_code',
        'Branch Name' => 'branches.name',
        'Contact No' => 'branches.contact_no',
        'Email' => 'branches.email',
        'Address' => 'branches.address',
        'District@no-sort@' => '',
        'Province@no-sort@' => '',
        'Country@no-sort@' => '',
        'Region@no-sort@' => '',
        'Status@no-sort@' => '',
        'Actions@no-sort@' => 'branches.id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_BRANCHES, 'vendor');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_BRANCHES, 'vendor');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_BRANCHES, 'vendor');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['export_route'] = "branches/export";

        $data['scripts'] = [];
        $data['addRoute'] = 'vendor.branches.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.branches.get-data', 'holder' => 'table-holder'];

        return view('common.table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_BRANCHES, 'vendor');

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

            $data = $this->branchService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

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
        $this->checkPermissionRedirect(Actions::CREATE_BRANCHES, 'vendor');

        $data['regions'] = Region::find(json_decode(auth()->user()->vendorCompany->limit_regions));
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('vendor::branches.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     * @param BranchRequest $request
     * @return Renderable
     */
    public function store(BranchRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_BRANCHES, 'vendor');

            $result = $this->branchService->createBranch($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_BRANCHES, $result['branch']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Branch created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        return view('vendor::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_BRANCHES, 'vendor');

        $data['regions'] = Region::find(json_decode(auth()->user()->vendorCompany->limit_regions));
        $data['loadCountriesUrl'] = route('load-countries');

        $data['branch'] = $this->branchService->getBranch($id); //Get branch with specified id

        $view = View::make('vendor::branches.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     * @param BranchRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(BranchRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_BRANCHES, 'vendor');

            $result = $this->branchService->updateBranch($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_BRANCHES, $result['branch']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Branch updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::DELETE_BRANCHES, 'vendor');

            $result = $this->branchService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_BRANCHES, $id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Branch deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::EDIT_BRANCHES, 'vendor');

            $result = $this->branchService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_BRANCHES, $result['branch']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Branch status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

            return $this->branchService->export($type, $columns, $searchValue);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function loadLimitCountries(Request $request) {
        try {
            $region_id = $request->selected_id;
            $limit_countries = json_decode(auth()->user()->vendorCompany->limit_countries);

            $regions = Region::findOrFail($region_id);
            $countries = $regions->countries->find($limit_countries);

            $temp = [];
            foreach ($countries as $country) {
                $obj = new \stdClass();
                $obj->id = $country->id;
                $obj->name = $country->country;
                array_push($temp, $obj);
            }

            $data['options'] = $temp;

            $view = View::make('common.common-dropdown-options', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function loadLimitProvinces(Request $request) {
        try {
            $country_id = $request->selected_id;
            $limit_provinces = json_decode(auth()->user()->vendorCompany->limit_provinces);

            $countries = Country::findOrFail($country_id);
            $provinces = $countries->provinces->find($limit_provinces);

            $temp = [];
            foreach ($provinces as $province) {
                $obj = new \stdClass();
                $obj->id = $province->id;
                $obj->name = $province->province;
                array_push($temp, $obj);
            }

            $data['options'] = $temp;

            $view = View::make('common.common-dropdown-options', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function loadLimitDistricts(Request $request) {
        try {
            $province_id = $request->selected_id;
            $limit_districts = json_decode(auth()->user()->vendorCompany->limit_districts);

            $provinces = Province::findOrFail($province_id);
            $districts = $provinces->districts->find($limit_districts);

            $temp = [];
            foreach ($districts as $district) {
                $obj = new \stdClass();
                $obj->id = $district->id;
                $obj->name = $district->district;
                array_push($temp, $obj);
            }

            $data['options'] = $temp;

            $view = View::make('common.common-dropdown-options', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
