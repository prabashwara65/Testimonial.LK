<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Response;

use App\Http\Constants\Actions;

class TestimonialFeedbackService extends MainService
{
    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm, $type, $status)
    {
        $testimonial = Response::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('type', '=', $type)->where('status', '=', $status)
            ->whereRaw("DATE(created_at) BETWEEN '".$filterForm['start_date']."' AND '".$filterForm['end_date']."' ");

        if (!empty($search)) {
            $testimonial = $this->search($testimonial, $columns, $search);
        }
        if (!empty($orderBy)) {
            $testimonial->orderBy($orderBy, $orderDirection);
        } else {
            $testimonial->orderBy('created_at', 'desc');
        }

        if ($filterForm['customer'] != '') {
            $testimonial->whereHas('user', function ($q) use ($filterForm) {
                $q->where(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $filterForm['customer'] . '%');
            });
        }
        if ($filterForm['employee'] != '') {
            $testimonial->whereHas('employee', function ($q) use ($filterForm) {
                $q->where(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $filterForm['employee'] . '%');
            });
        }
        if ($filterForm['campaign_id'] != 'Any') {
            $testimonial->where('campaign_id', $filterForm['campaign_id']);
        }
        if ($filterForm['response_type'] != 'Any') {
            $testimonial->where('response_type', $filterForm['response_type']);
        }
        if ($filterForm['product_id'] != 'Any') {
            $testimonial->where('product_id', $filterForm['product_id']);
        }
        if ($filterForm['subproduct_id'] != 'Any') {
            $testimonial->where('subproduct_id', $filterForm['subproduct_id']);
        }
        if ($filterForm['input_source'] != 'Any') {
            $testimonial->where('input_source', $filterForm['input_source']);
        }

        /// get the filtered row count before limiting the results
        $rows = $testimonial->get();
        $count = count($rows);

        // limit the results for pagination
        $testimonial->offset($offset)->limit($limit);
        $rows = $testimonial->get();

        $data = [];
        if($status == 'approved') {
            foreach ($rows as $testimonial) {
                $rating = '';
                for ($i = 0; $i < $testimonial->rating; $i++) { $rating .= '★'; }

                if(isset($testimonial->emp_id)){ $employee_name = $testimonial->employee->name." ".$testimonial->employee->last_name; }else{ $employee_name = '-----'; }
                if(isset($testimonial->campaign_id)){ $campaign_name = $testimonial->campaign->campaign_name; }else{ $campaign_name = '-----'; }

                $temp = [
                    $testimonial->created_at->format('Y-m-d h:i A'),
                    $testimonial->user->name." ".$testimonial->user->last_name,
                    $employee_name,
                    $campaign_name,
                    $testimonial->product->product_name,
                    $testimonial->subproduct->subproduct_name,
                    $testimonial->response_type,
                    $testimonial->input_source,
                    $rating,
                    $this->generateActionButtons('vendor.testimonial-feedback', $testimonial->id, ['view' => Actions::VIEW_TESTIMONIALS]),
                    $this->generateActionButtons('vendor.testimonial-feedback', $testimonial->id, ['view' => false, 'edit' => Actions::EDIT_TESTIMONIALS, 'delete' => false])
                ];
                array_push($data, $temp);
            }
        }elseif($status == 'reject') {
            foreach ($rows as $testimonial) {
                if(isset($testimonial->emp_id)){ $employee_name = $testimonial->employee->name." ".$testimonial->employee->last_name; }else{ $employee_name = '-----'; }
                if(isset($testimonial->campaign_id)){ $campaign_name = $testimonial->campaign->campaign_name; }else{ $campaign_name = '-----'; }

                $temp = [
                    $testimonial->created_at->format('Y-m-d h:i A'),
                    $testimonial->user->name." ".$testimonial->user->last_name,
                    $employee_name,
                    $campaign_name,
                    $testimonial->product->product_name,
                    $testimonial->subproduct->subproduct_name,
                    $testimonial->response_type,
                    $testimonial->input_source,
                    $testimonial->reject_reason,
                    $this->generateActionButtons('vendor.testimonial-feedback', $testimonial->id, ['view' => Actions::VIEW_TESTIMONIALS]),
                    $this->generateActionButtons('vendor.testimonial-feedback', $testimonial->id, ['view' => false, 'edit' => Actions::EDIT_TESTIMONIALS, 'delete' => false])
                ];
                array_push($data, $temp);
            }
        }elseif($status == 'pending') {
            foreach ($rows as $testimonial) {
                
                if(isset($testimonial->emp_id)){ $employee_name = $testimonial->employee->name." ".$testimonial->employee->last_name; }else{ $employee_name = '-----'; }
                if(isset($testimonial->campaign_id)){ $campaign_name = $testimonial->campaign->campaign_name; }else{ $campaign_name = '-----'; }

                $temp = [
                    $testimonial->created_at->format('Y-m-d h:i A'),
                    $testimonial->user->name." ".$testimonial->user->last_name,
                    $employee_name,
                    $campaign_name,
                    $testimonial->product->product_name,
                    $testimonial->subproduct->subproduct_name,
                    $testimonial->response_type,
                    $testimonial->input_source,
                    $testimonial->diff_in_days,
                    $this->generateActionButtons('vendor.testimonial-feedback', $testimonial->id, ['view' => Actions::VIEW_TESTIMONIALS]),
                    $this->generateActionButtons('vendor.testimonial-feedback', $testimonial->id, ['view' => false, 'edit' => Actions::EDIT_TESTIMONIALS, 'delete' => false])
                ];
                array_push($data, $temp);
            }
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Response::where('vendor_company_id', '=', auth()->user()->vendor_company_id)->where('type', '=', $type)->where('status', '=', $status)->count(); // count of all the records in the database table

        return $out;
    }

    public function updateTestimonial($request, $id)
    {
        try {
            $testimonial = Response::find($id); //Get testimonial specified by id

            $input = $request->only(['status', 'reject_reason', 'rating']);
            if($input['status'] == 'reject') {
                $input['rating'] = 0;
            } elseif ($input['status'] == 'approved') {
                $input['reject_reason'] = '';
            } else {
                $input['rating'] = 0;
                $input['reject_reason'] = '';
            }
            $testimonial->fill($input)->save();

            return ['status' => 'success', 'testimonial' => $testimonial];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue, $filterForm, $response_type, $status)
    {
        try {

            if($response_type == 1) {
                $title = 'Testimonial - ' . ucfirst($status);
            } else {
                $title = 'Feedback - ' . ucfirst($status);
            }

            $model = Response::where('responses.vendor_company_id', '=', auth()->user()->vendor_company_id)->where('responses.type', '=', $response_type)->where('responses.status', '=', $status)
                            ->whereRaw("DATE(responses.created_at) BETWEEN '".$filterForm['start_date']."' AND '".$filterForm['end_date']."' ")
                            ->leftjoin('users', 'responses.customer_id', 'users.id')
                            ->leftjoin('vendors', 'responses.emp_id', 'vendors.id')
                            ->leftjoin('campaigns', 'responses.campaign_id', 'campaigns.id')
                            ->leftjoin('products', 'responses.product_id', 'products.id')
                            ->leftjoin('subproducts', 'responses.subproduct_id', 'subproducts.id');

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            if($status == 'approved') {

                $columnHeaders = array('Date', 'Customer Name', 'Employee Name', 'Campaign', 'Product', 'Subproduct', 'Response Type', 'Input Source', 'Rating');
                $tableColumns = array('created_at', 'customer_name', 'emp_name', 'campaign', 'product', 'subproduct', 'response_type', 'input_source', 'rating');
                
                $select = array('responses.created_at', 'responses.response_type', 'responses.input_source', 'rating');

            } elseif($status == 'reject') {

                $columnHeaders = array('Date', 'Customer Name', 'Employee Name', 'Campaign', 'Product', 'Subproduct', 'Response Type', 'Input Source', 'Reject Reason');
                $tableColumns = array('created_at', 'customer_name', 'emp_name', 'campaign', 'product', 'subproduct', 'response_type', 'input_source', 'reject_reason');
                
                $select = array('responses.created_at', 'responses.response_type', 'responses.input_source', 'responses.reject_reason');

            } elseif($status == 'pending') {

                $columnHeaders = array('Date', 'Customer Name', 'Employee Name', 'Campaign', 'Product', 'Subproduct', 'Response Type', 'Input Source', 'Number of Pending Days');
                $tableColumns = array('created_at', 'customer_name', 'emp_name', 'campaign', 'product', 'subproduct', 'response_type', 'input_source', 'diff_in_days');
                
                $select = array('responses.created_at', 'responses.response_type', 'responses.input_source');

            }

            if ($filterForm['customer'] != '') {
                $model->whereHas('user', function ($q) use ($filterForm) {
                    $q->where(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $filterForm['customer'] . '%');
                });
            }
            if ($filterForm['employee'] != '') {
                $model->whereHas('employee', function ($q) use ($filterForm) {
                    $q->where(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $filterForm['employee'] . '%');
                });
            }
            if ($filterForm['campaign_id'] != 'Any') {
                $model->where('campaign_id', $filterForm['campaign_id']);
            }
            if ($filterForm['response_type'] != 'Any') {
                $model->where('response_type', $filterForm['response_type']);
            }
            if ($filterForm['product_id'] != 'Any') {
                $model->where('product_id', $filterForm['product_id']);
            }
            if ($filterForm['subproduct_id'] != 'Any') {
                $model->where('subproduct_id', $filterForm['subproduct_id']);
            }
            if ($filterForm['input_source'] != 'Any') {
                $model->where('input_source', $filterForm['input_source']);
            }

            array_push( $select,
                        DB::raw("CONCAT(users.name, ' ', users.last_name) AS 'customer_name'"),
                        DB::raw("CONCAT(vendors.name, ' ', vendors.last_name) AS 'emp_name'"),
                        DB::raw("campaigns.campaign_name AS 'campaign'"),
                        DB::raw("products.product_name AS 'product'"),
                        DB::raw("subproducts.subproduct_name AS 'subproduct'")
                    );
            $model = $model->orderBy('created_at', 'DESC')->get($select);

            return $this->exportReport($type, $searchValue, $title, $columnHeaders, $tableColumns, $model);
    
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function search($model, $columns, $search)
    {
        $model->where(function($query) use ($columns, $search){
            $query = $this->generateWhereLikeQuery($query, $columns, $search);
        });

        return $model;
    }
}