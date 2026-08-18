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

use Modules\Admin\Http\Services\ProductReportService;

use App\Http\Constants\Actions;


class ProductReportController extends Controller
{

    /** @var string Title shown at top left of the page */
    private $pageTitle = "Product Report";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Vendor Company@no-sort@' => '',
        'BR Number@no-sort@' => '',
        'Product Code@no-sort@' => '',
        'Product Name@no-sort@' => '',
        'Subproduct Code' => 'subproduct_code',
        'Subproduct Name' => 'subproduct_name',
    ];

    public function __construct(ProductReportService $productReportService)
    {
        $this->productReportService = $productReportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->checkPermissionRedirect(Actions::VIEW_PRODUCT_REPORT, 'admin');

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['viewSingle'] = true;

        $data['getData'] = ['url' => 'admin.product-report.get-data', 'holder' => 'table-holder'];

        $data['table_before_section'] = View::make('admin::product_report.filter-form', $this->getFilterData())->render();

        return view('common/table-holder', $data);
    }

    public function getFilterData()
    {
        $filterData['vendorCompanies'] = VendorCompany::where('status', 1)->get();

        return $filterData;
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_PRODUCT_REPORT, 'admin');

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

            $data = $this->productReportService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

   
}