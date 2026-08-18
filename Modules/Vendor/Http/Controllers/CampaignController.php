<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\CampaignRequest;

use App\Models\Target;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Vendor;

use Modules\Vendor\Http\Services\CampaignService;

use App\Http\Constants\Actions;


class CampaignController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Campaign";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Campaign Name' => 'campaigns.campaign_name',
        'Campaign Type' => 'campaigns.campaign_type',
        'Target Type@no-sort@' => '',
        'Response Type' => 'campaigns.response_type',
        'Incentive Rate' => 'campaigns.incentive_rate',
        'Start Date' => 'campaigns.start_date',
        'End Date' => 'campaigns.end_date',
        'Status@no-sort@' => 'campaigns.status',
        'Actions@no-sort@' => 'id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_CAMPAIGNS, 'vendor');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_CAMPAIGNS, 'vendor');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_CAMPAIGNS, 'vendor');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['export_route'] = "campaigns/export";

        $data['scripts'] = ['campaigns.js'];
        $data['addRoute'] = 'vendor.campaigns.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.campaigns.get-data', 'holder' => 'table-holder'];

        $data['table_before_section'] = View::make('vendor::campaigns.filter-form')->render();

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_CAMPAIGNS, 'vendor');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];
            $columns = $this->columns;

            $filterForm = $input['form'];

            $orderBy = '';
            $orderDirection = '';
            if (isset($input['order'])) {
                $orderBy = $this->getOrderByColumn($columns, $input['order'][0]['column']);
                $orderDirection = $input['order'][0]['dir'];
            }

            $data = $this->campaignService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm);

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
        $this->checkPermissionRedirect(Actions::CREATE_CAMPAIGNS, 'vendor');

        $data['targets'] = Target::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $data['branches'] = Branch::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('status', 1)->get();

        $data['loadEmployeeUrl'] = route('vendor.campaigns.load-employee');

        $view = View::make('vendor::campaigns.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_CAMPAIGNS, 'vendor');

            if (!isset($request['product_id'])) {
                $outPutArray = array('status' => 'warning', 'message' => 'At least one Product is needed to create a Campaign', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
                return $outPutArray;
            }

            $result = $this->campaignService->createCampaign($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_CAMPAIGNS, $result['campaign']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Campaign created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        $this->checkPermissionRedirect(Actions::EDIT_CAMPAIGNS, 'vendor');

        $data['campaign'] = $this->campaignService->getCampaign($id);
        $data['targets'] = Target::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $data['branches'] = Branch::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $data['employees'] = Vendor::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $data['products'] = Product::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('status', 1)->get();

        $data['loadSubproductUrl'] = route('vendor.campaigns.load-subproduct');
        $data['loadEmployeeUrl'] = route('vendor.campaigns.load-employee');

        $view = View::make('vendor::campaigns.edit', $data)->render();
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
    public function update(CampaignRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_CAMPAIGNS, 'vendor');

            $result = $this->campaignService->updateCampaign($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_CAMPAIGNS, $result['campaign']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Campaign updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::EDIT_CAMPAIGNS, 'vendor');

            $result = $this->campaignService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_CAMPAIGNS, $result['campaign']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Campaign status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

    public function export(Request $request, $type = null, $searchValue = null)
    {
        try {
            $columns = $this->columns;
            $filterForm = $request->all();

            return $this->campaignService->export($type, $columns, $searchValue, $filterForm);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function getCampaignTemplate($count) {
        $data['count'] = $count;
        $data['products'] = Product::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('status', 1)->get();

        $data['loadSubproductUrl'] = route('vendor.campaigns.load-subproduct');

        $view = View::make('vendor::campaigns.campaign_template', $data)->render();
        $outPutArray = array('status' => 'success', 'data' => $view);
        return $outPutArray;
    }

    public function loadSubproduct(Request $request) {
        try {
            $product_id = $request->selected_id;

            $product = Product::findOrFail($product_id);
            $subproducts = $product->subproducts->where('status', 1);

            $temp = [];
            foreach ($subproducts as $subproduct) {
                $obj = new \stdClass();
                $obj->id = $subproduct->id;
                $obj->name = $subproduct->subproduct_code . ' - ' . $subproduct->subproduct_name;
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

    public function loadEmployee(Request $request) {
        try {

            $temp = [];

            if(isset($request->selected_id))
            {
                foreach($request->selected_id as $branch_id)
                {
                    $branch = Branch::findOrFail($branch_id);
                    $vendors = $branch->vendors->where('status', 1);

                    foreach ($vendors as $vendor) {
                        $obj = new \stdClass();
                        $obj->id = $vendor->id;
                        $obj->name = $vendor->emp_id . ' - ' . $vendor->name . ' ' . $vendor->last_name;
                        array_push($temp, $obj);
                    }

                }
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
