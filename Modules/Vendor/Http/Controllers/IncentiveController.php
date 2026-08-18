<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use App\Models\Branch;

use Modules\Vendor\Http\Services\IncentiveService;

use App\Http\Constants\Actions;


class IncentiveController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Incentives Payment";

    private $columnsPaid = [
        'Emp ID' => 'emp_id',
        'Employee Name' => 'emp_name',
        'Branch' => 'branch',
        'NIC' => 'nic',
        'Back Acc No' => 'bank_account',
        'Incentive Amount' => 'incentive_amount',
        'Paid Date' => 'paid_date',
        'Actions@no-sort@' => 'actions'
    ];

    private $columnsReject = [
        'Emp ID' => 'emp_id',
        'Employee Name' => 'emp_name',
        'Branch' => 'branch',
        'NIC' => 'nic',
        'Back Acc No' => 'bank_account',
        'Incentive Amount' => 'incentive_amount',
        'Reject Date' => 'reject_date',
        'Actions@no-sort@' => 'actions'
    ];

    private $columnsPending = [
        'Emp ID' => 'emp_id',
        'Employee Name' => 'emp_name',
        'Branch' => 'branch',
        'Campaign Name' => 'campaign',
        'Target Type' => 'target',
        'Common Target' => 'common_target',
        'Video Target' => 'video_target',
        'Audio Target' => 'audio_target',
        'Image Target' => 'image_target',
        'Text Target' => 'text_target',
        'Incentive Rate' => 'incentive_rate',
        'Incentive Amount' => 'incentive_amount',
        'End Date' => 'end_date',
        'Actions@no-sort@' => 'actions'
    ];

    public function __construct(IncentiveService $incentiveService)
    {
        $this->incentiveService = $incentiveService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function paid()
    {
        $this->checkPermissionRedirect(Actions::VIEW_INCENTIVEPAYMENTS, 'vendor');

        $dataTable = [
            'table' => 'dataTable',
            'url' => 'vendor.incentives.paid.get-data',
            'columns' => $this->columnsPaid
        ];

        $data['title'] = $this->pageTitle;
        $data['status'] = 'paid';
        $data['export_route'] = "paid/export";
        $data['scripts'] = ['testimonial.js'];

        $data['table_before_section'] = View::make('vendor::incentives/filter-form')->render();

        return view('vendor::incentives/table-holder', ['dataTable' => $dataTable], $data);
    }

    public function reject()
    {
        $this->checkPermissionRedirect(Actions::VIEW_INCENTIVEPAYMENTS, 'vendor');

        $dataTable = [
            'table' => 'dataTable',
            'url' => 'vendor.incentives.reject.get-data',
            'columns' => $this->columnsReject
        ];

        $data['title'] = $this->pageTitle;
        $data['status'] = 'reject';
        $data['export_route'] = "reject/export";
        $data['scripts'] = ['testimonial.js'];

        $data['table_before_section'] = View::make('vendor::incentives/filter-form')->render();

        return view('vendor::incentives/table-holder', ['dataTable' => $dataTable], $data);
    }

    public function pending()
    {
        $this->checkPermissionRedirect(Actions::VIEW_INCENTIVEPAYMENTS, 'vendor');

        $dataTable = [
            'table' => 'dataTable',
            'url' => 'vendor.incentives.pending.get-data',
            'columns' => $this->columnsPending
        ];

        $data['title'] = $this->pageTitle;
        $data['status'] = 'pending';
        $data['export_route'] = "pending/export";
        $data['scripts'] = ['testimonial.js'];

        $data['table_before_section'] = View::make('vendor::incentives/filter-form')->render();

        return view('vendor::incentives/table-holder', ['dataTable' => $dataTable], $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_INCENTIVEPAYMENTS, 'vendor');

            $status = request()->segment(4);
            $searchData = $request->form;

            return $this->incentiveService->getDataTable($status, $searchData);
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function edit($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_INCENTIVEPAYMENTS, 'vendor');

            $data['incentive'] = $id;

            $view = View::make('vendor::incentives.edit', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_INCENTIVEPAYMENTS, 'vendor');

            $result = $this->incentiveService->markAsPaid($request, $id);

            if ($result['status'] == 'success') {
                // $this->logAction(Actions::EDIT_SUBPRODUCTS, $result['subproduct']->subproduct_id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Mark as paid/reject successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

    public function destroy($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_INCENTIVEPAYMENTS, 'vendor');

            $result = $this->incentiveService->markAsUnpaid($id);

            if ($result['status'] == 'success') {
                $outPutArray = array('status' => 'success', 'message' => 'Mark as unpaid successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $status = request()->segment(4);

            if($status == 'paid') {
                $columns = $this->columnsPaid;
            } elseif($status == 'reject') {
                $columns = $this->columnsReject;
            } else {
                $columns = $this->columnsPending;
            }

            $filterForm = $request->all();

            return $this->incentiveService->export($type, $columns, $searchValue, $filterForm, $status);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
