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
use App\Models\Response;
use App\Models\ResponseRecord;

use Modules\Admin\Http\Services\TestimonialFeedbackService;

use App\Http\Constants\Actions;

class TestimonialFeedbackController extends Controller
{
    /** @var array The names and relevent property name of columns in the table */
    private $columnsApproved = [
        'Date' => 'created_at',
        'Vendor Company' => 'vendor_company_id',
        'Customer Name' => 'customer_id',
        'Employee Name' => 'emp_id',
        'Campaign' => 'campaign_id',
        'Product' => 'product_id',
        'Subproduct' => 'subproduct_id',
        'Response Type' => 'response_type',
        'Input Source' => 'input_source',
        'Rating' => 'rating',
        'View@no-sort@' => '',
        'Actions@no-sort@' => 'id'
    ];

    private $columnsReject = [
        'Date' => 'created_at',
        'Vendor Company' => 'vendor_company_id',
        'Customer Name' => 'customer_id',
        'Employee Name' => 'emp_id',
        'Campaign' => 'campaign_id',
        'Product' => 'product_id',
        'Subproduct' => 'subproduct_id',
        'Response Type' => 'response_type',
        'Input Source' => 'input_source',
        'Reject Reason' => 'reject_reason',
        'View@no-sort@' => '',
        'Actions@no-sort@' => 'id'
    ];

    private $columnsPending = [
        'Date' => 'created_at',
        'Vendor Company' => 'vendor_company_id',
        'Customer Name' => 'customer_id',
        'Employee Name' => 'emp_id',
        'Campaign' => 'campaign_id',
        'Product' => 'product_id',
        'Subproduct' => 'subproduct_id',
        'Response Type' => 'response_type',
        'Input Source' => 'input_source',
        'Number of pending days' => '',
        'View@no-sort@' => '',
        'Actions@no-sort@' => 'id'
    ];

    public function __construct(TestimonialFeedbackService $testimonialFeedbackService)
    {
        $this->testimonialFeedbackService = $testimonialFeedbackService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approved()
    {
        $this->checkPermissionRedirect(Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

        $data['columns'] = $this->columnsApproved;
        $data['status'] = 'approved';

        $data['addPermission'] = false;
        $data['editPermission'] = false;
        $data['deletePermission'] = false;

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = '';

        $data['viewSingle'] = false;

        if (request()->segment(2) == "testimonials") {
            $data['title'] = "Vendor Wise Testimonials";
            $data['getData'] = ['url' => 'admin.testimonials.approved.get-data', 'holder' => 'table-holder'];
        } elseif (request()->segment(2) == "feedbacks") {
            $data['title'] = "Vendor Wise Feedbacks";
            $data['getData'] = ['url' => 'admin.feedbacks.approved.get-data', 'holder' => 'table-holder'];
        }

        $data['table_before_section'] = View::make('admin::testimonial_feedback/filter-form', $this->getFilterData())->render();

        return view('admin::testimonial_feedback/table-holder', $data);
    }

    public function reject()
    {
        $this->checkPermissionRedirect(Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

        $data['columns'] = $this->columnsReject;
        $data['status'] = 'reject';

        $data['addPermission'] = false;
        $data['editPermission'] = false;
        $data['deletePermission'] = false;

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = '';

        $data['viewSingle'] = false;

        if (request()->segment(2) == "testimonials") {
            $data['title'] = "Vendor Wise Testimonials";
            $data['getData'] = ['url' => 'admin.testimonials.reject.get-data', 'holder' => 'table-holder'];
        } elseif (request()->segment(2) == "feedbacks") {
            $data['title'] = "Vendor Wise Feedbacks";
            $data['getData'] = ['url' => 'admin.feedbacks.reject.get-data', 'holder' => 'table-holder'];
        }

        $data['table_before_section'] = View::make('admin::testimonial_feedback/filter-form', $this->getFilterData())->render();

        return view('admin::testimonial_feedback/table-holder', $data);
    }

    public function pending()
    {
        $this->checkPermissionRedirect(Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

        $data['columns'] = $this->columnsPending;
        $data['status'] = 'pending';

        $data['addPermission'] = false;
        $data['editPermission'] = false;
        $data['deletePermission'] = false;

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = '';

        $data['viewSingle'] = false;

        if (request()->segment(2) == "testimonials") {
            $data['title'] = "Vendor Wise Testimonials";
            $data['getData'] = ['url' => 'admin.testimonials.pending.get-data', 'holder' => 'table-holder'];
        } elseif (request()->segment(2) == "feedbacks") {
            $data['title'] = "Vendor Wise Feedbacks";
            $data['getData'] = ['url' => 'admin.feedbacks.pending.get-data', 'holder' => 'table-holder'];
        }


        $data['table_before_section'] = View::make('admin::testimonial_feedback/filter-form', $this->getFilterData())->render();

        return view('admin::testimonial_feedback/table-holder', $data);
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
            $this->checkPermissionRedirect(Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];

            if (request()->segment(2) == "testimonials") {
                $type = 1;
            } elseif (request()->segment(2) == "feedbacks") {
                $type = 2;
            }

            $status = request()->segment(3);

            if ($status == 'approved') {
                $columns = $this->columnsApproved;
            } elseif ($status == 'reject') {
                $columns = $this->columnsReject;
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

            $data = $this->testimonialFeedbackService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm, $type, $status);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function showQuestionnaire($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

            $data['questionnaires'] = Response::find($id);

            $view = View::make('admin::testimonial_feedback/single', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'sm');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function showRecord($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

            $data['records'] = ResponseRecord::whereHas('response', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $view = View::make('admin::testimonial_feedback/single', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'sm');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function edit($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

            $data['testimonial'] = Response::find($id);

            $view = View::make('admin::testimonial_feedback.edit', $data)->render();
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
            $this->checkPermissionRedirect(Actions::EDIT_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'admin');

            $result = $this->testimonialFeedbackService->updateTestimonial($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_VENDOR_WISE_TESTIMONIAL_FEEDBACK, $result['testimonial']->id, json_encode(request()->all()), json_encode($result));

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
}
