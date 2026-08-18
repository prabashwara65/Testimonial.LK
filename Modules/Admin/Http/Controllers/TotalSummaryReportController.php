<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use App\Models\VendorCompany;
use App\Models\Vendor;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\Subproduct;

use Modules\Admin\Http\Services\TotalSummaryReportService;

use App\Http\Constants\Actions;


class TotalSummaryReportController extends Controller
{

    /** @var string Title shown at top left of the page */
    private $pageTitle = "Total Summary Report";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Date' => 'created_at',
        'Vendor Company' => 'vendor_company_id',
        'Customer Name' => 'customer_id',
        'Employee Name' => 'emp_id',
        'Campaign' => 'campaign_id',
        'Product' => 'product_id',
        'Subproduct' => 'subproduct_id',
        'Type' => 'type',
        'Response Type' => 'response_type',
        'Input Source' => 'input_source',
        'Status' => 'status'
    ];

    public function __construct(TotalSummaryReportService $totalSummaryReportService)
    {
        $this->totalSummaryReportService = $totalSummaryReportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_TOTAL_SUMMARY_REPORT, 'admin');

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['viewSingle'] = true;

        $data['getData'] = ['url' => 'admin.total-summary-report.get-data', 'holder' => 'table-holder'];

        $data['table_before_section'] = View::make('admin::total_summary_report.filter-form', $this->getFilterData())->render();

        return view('common/table-holder', $data);
    }

    public function getFilterData()
    {
        $filterData['vendorCompanies'] = VendorCompany::where('status', 1)->get();

        $filterData['vendors'] = Vendor::when(auth()->user()->vendor_company_id, function ($q) {
            return $q->where('vendor_company_id', auth()->user()->vendor_company_id);
        })->where('status', 1)->get();

        $filterData['campaigns'] = Campaign::when(auth()->user()->vendor_company_id, function ($q) {
            return $q->where('vendor_company_id', auth()->user()->vendor_company_id);
        })->where('status', 1)->get();

        $filterData['products'] = Product::when(auth()->user()->vendor_company_id, function ($q) {
            return $q->where('vendor_company_id', auth()->user()->vendor_company_id);
        })->where('status', 1)->get();

        $filterData['subproducts'] = Subproduct::whereHas('product', function ($q) {
            $q->when(auth()->user()->vendor_company_id, function ($q) {
                return $q->where('vendor_company_id', auth()->user()->vendor_company_id);
            })->where('status', 1);
        })->get();

        return $filterData;
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_TOTAL_SUMMARY_REPORT, 'admin');

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

            $data = $this->totalSummaryReportService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
