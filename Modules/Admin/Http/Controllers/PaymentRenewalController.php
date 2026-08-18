<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use App\Models\Branch;

use Modules\Admin\Http\Services\PaymentRenewalService;

use App\Http\Constants\Actions;
use App\Models\VendorCompany;

class PaymentRenewalController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Payment Renewal";

    /** @var array The names and relevent property name of columns in the table */
    private $columnsPaid = [
        'Company Name' => '',
        'Company Address' => '',
        'BR No' => '',
        'Contact No' => '',
        'Email' => '',
        'Region' => '',
        'Country' => '',
        'Renewal Date' => 'renewal_date',
        'Renewal Charge ' => 'renewal_charge',
        'Paid Date' => 'paid_date',
        'Actions@no-sort@' => ''
    ];

    private $columnsPending = [
        'Company Name' => '',
        'Company Address' => '',
        'BR No' => '',
        'Contact No' => '',
        'Email' => '',
        'Region' => '',
        'Country' => '',
        'Next Renewal Date' => '',
        'Renewal Charge ' => 'renewal_charge',
        'Number of Pending Days' => '',
        'Actions@no-sort@' => ''
    ];

    public function __construct(PaymentRenewalService $paymentRenewalService)
    {
        $this->paymentRenewalService = $paymentRenewalService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paid()
    {
        $this->checkPermissionRedirect(Actions::VIEW_PAYMENT_RENEWALS, 'admin');

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columnsPaid;
        $data['status'] = 'paid';

        $data['getData'] = ['url' => 'admin.payment-renewals.paid.get-data', 'holder' => 'table-holder'];

        $filterData['companies'] = VendorCompany::where('status', 1)->get();

        $data['table_before_section'] = View::make('admin::payment_renewals.filter-form', $filterData)->render();

        return view('admin::payment_renewals.table-holder', $data);
    }

    public function pending()
    {
        $this->checkPermissionRedirect(Actions::VIEW_PAYMENT_RENEWALS, 'admin');

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columnsPending;
        $data['status'] = 'pending';

        $data['getData'] = ['url' => 'admin.payment-renewals.pending.get-data', 'holder' => 'table-holder'];

        $filterData['companies'] = VendorCompany::where('status', 1)->get();

        $data['table_before_section'] = View::make('admin::payment_renewals.filter-form', $filterData)->render();

        return view('admin::payment_renewals.table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_PAYMENT_RENEWALS, 'admin');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];
            $status = request()->segment(3);

            if ($status == 'paid') {
                $columns = $this->columnsPaid;
            } elseif ($status == 'pending') {
                $columns = $this->columnsPending;
            }

            $filterForm = $input['form'];

            $orderBy = '';
            $orderDirection = '';
            if (isset($input['order'])) {
                $orderBy = $this->getOrderByColumn($columns, $input['order'][0]['column']);
                $orderDirection = $input['order'][0]['dir'];
            }

            $data = $this->paymentRenewalService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm, $status);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function edit($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_PAYMENT_RENEWALS, 'admin');

            $data['incentive'] = $id;

            $view = View::make('admin::payment_renewals.edit', $data)->render();
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
            $this->checkPermissionRedirect(Actions::VIEW_PAYMENT_RENEWALS, 'admin');

            $result = $this->paymentRenewalService->markAsPaid($request, $id);

            if ($result['status'] == 'success') {
                // $this->logAction(Actions::EDIT_SUBPRODUCTS, $result['subproduct']->subproduct_id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Mark as paid successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::VIEW_PAYMENT_RENEWALS, 'admin');

            $result = $this->paymentRenewalService->markAsUnpaid($id);

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
}
