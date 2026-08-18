<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use App\Models\Vendor;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\Subproduct;
use App\Models\Response;
use App\Models\ResponseRecord;
use App\Models\ResponseQuestion;

use Modules\Vendor\Http\Services\TestimonialFeedbackService;

use App\Http\Constants\Actions;

class TestimonialFeedbackController extends Controller
{
    /** @var array The names and relevent property name of columns in the table */
    private $columnsApproved = [
        'Date' => 'created_at',
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
        $this->checkPermissionRedirect(Actions::VIEW_TESTIMONIALS, 'vendor');

        $data['columns'] = $this->columnsApproved;
        $data['status'] = 'approved';

        $data['addPermission'] = false;
        $data['editPermission'] = false;
        $data['deletePermission'] = false;

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = '';

        $data['viewSingle'] = false;

        $data['export_route'] = "approved/export";

        if(request()->segment(3) == "testimonials"){
            $data['title'] = "Testimonials";
            $data['getData'] = ['url' => 'vendor.testimonials.approved.get-data', 'holder' => 'table-holder'];
        }elseif(request()->segment(3) == "feedbacks"){
            $data['title'] = "Feedbacks";
            $data['getData'] = ['url' => 'vendor.feedbacks.approved.get-data', 'holder' => 'table-holder'];
        }

        $data['table_before_section'] = View::make('vendor::testimonial_feedback/filter-form', $this->getFilterData())->render();

        return view('vendor::testimonial_feedback/table-holder', $data);
    }

    public function reject()
    {
        $this->checkPermissionRedirect(Actions::VIEW_TESTIMONIALS, 'vendor');

        $data['columns'] = $this->columnsReject;
        $data['status'] = 'reject';

        $data['addPermission'] = false;
        $data['editPermission'] = false;
        $data['deletePermission'] = false;

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = '';

        $data['viewSingle'] = false;

        $data['export_route'] = "reject/export";

        if(request()->segment(3) == "testimonials"){
            $data['title'] = "Testimonials";
            $data['getData'] = ['url' => 'vendor.testimonials.reject.get-data', 'holder' => 'table-holder'];
        }elseif(request()->segment(3) == "feedbacks"){
            $data['title'] = "Feedbacks";
            $data['getData'] = ['url' => 'vendor.feedbacks.reject.get-data', 'holder' => 'table-holder'];
        }

        $data['table_before_section'] = View::make('vendor::testimonial_feedback/filter-form', $this->getFilterData())->render();

        return view('vendor::testimonial_feedback/table-holder', $data);
    }

    public function pending()
    {
        $this->checkPermissionRedirect(Actions::VIEW_TESTIMONIALS, 'vendor');

        $data['columns'] = $this->columnsPending;
        $data['status'] = 'pending';

        $data['addPermission'] = false;
        $data['editPermission'] = false;
        $data['deletePermission'] = false;

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = '';

        $data['viewSingle'] = false;

        $data['export_route'] = "pending/export";

        if(request()->segment(3) == "testimonials"){
            $data['title'] = "Testimonials";
            $data['getData'] = ['url' => 'vendor.testimonials.pending.get-data', 'holder' => 'table-holder'];
        }elseif(request()->segment(3) == "feedbacks"){
            $data['title'] = "Feedbacks";
            $data['getData'] = ['url' => 'vendor.feedbacks.pending.get-data', 'holder' => 'table-holder'];
        }
        

        $data['table_before_section'] = View::make('vendor::testimonial_feedback/filter-form', $this->getFilterData())->render();

        return view('vendor::testimonial_feedback/table-holder', $data);
    }

    public function getFilterData()
    {
        $filterData['vendors'] = Vendor::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $filterData['campaigns'] = Campaign::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $filterData['products'] = Product::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $filterData['subproducts'] = Subproduct::whereHas('product', function ($q) {
                                        $q->where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1);
                                    })->get();

        return $filterData;
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_TESTIMONIALS, 'vendor');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];

            if(request()->segment(3) == "testimonials") {
                $type = 1;
            } elseif(request()->segment(3) == "feedbacks") {
                $type = 2;
            }

            $status = request()->segment(4);

            if($status == 'approved') {
                $columns = $this->columnsApproved;
            } elseif($status == 'reject') {
                $columns = $this->columnsReject;
            } elseif($status == 'pending') {
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

    public function show($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_TESTIMONIALS, 'vendor');

            $response = Response::find($id);

            $data['geo_data'] = $response->only('latitude', 'longitude', 'geo_address');

            if($response->response_type == 'Questionnaire') {
                $data['questionnaires'] = $response->responseQuestions;
            }
            else {
                $data['record'] = $response->responseRecord;
            }

            $view = View::make('vendor::testimonial_feedback.single', $data)->render();
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
            $this->checkPermissionRedirect(Actions::VIEW_TESTIMONIALS, 'vendor');

            $data['testimonial'] = Response::find($id);

            $view = View::make('vendor::testimonial_feedback.edit', $data)->render();
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
            $this->checkPermissionRedirect(Actions::EDIT_TESTIMONIALS, 'vendor');

            $result = $this->testimonialFeedbackService->updateTestimonial($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_TESTIMONIALS, $result['testimonial']->id, json_encode(request()->all()), json_encode($result));

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

    public function export(Request $request, $type = null, $searchValue = null)
    {
        try {
            if(request()->segment(3) == "testimonials") {
                $response_type = 1;
            } elseif(request()->segment(3) == "feedbacks") {
                $response_type = 2;
            }

            $status = request()->segment(4);

            if($status == 'approved') {
                $columns = $this->columnsApproved;
            } elseif($status == 'reject') {
                $columns = $this->columnsReject;
            } elseif($status == 'pending') {
                $columns = $this->columnsPending;
            }

            $filterForm = $request->all();

            return $this->testimonialFeedbackService->export($type, $columns, $searchValue, $filterForm, $response_type, $status);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
