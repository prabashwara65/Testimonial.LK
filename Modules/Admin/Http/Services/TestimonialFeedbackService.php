<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Response;

use App\Http\Constants\Actions;

class TestimonialFeedbackService extends MainService
{
    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm, $type, $status)
    {
        $testimonial = Response::where('type', '=', $type)->where('status', '=', $status)
            ->whereRaw("DATE(created_at) BETWEEN '".$filterForm['start_date']."' AND '".$filterForm['end_date']."' ");

        if (!empty($search)) {
            $testimonial->where(function($query) use ($columns, $search){
                $query = $this->generateWhereLikeQuery($query, $columns, $search);
            });
        }
        if (!empty($orderBy)) {
            $testimonial->orderBy($orderBy, $orderDirection);
        } else {
            $testimonial->orderBy('created_at', 'desc');
        }

        if ($filterForm['vendor_company_id'] != 'Any') {
            $testimonial->where('vendor_company_id', $filterForm['vendor_company_id']);
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

                $viewbtn = ($testimonial->response_type == 'Questionnaire') ? $this->generateActionButtons('admin.testimonial-feedback.questionnaire', $testimonial->id, ['view' => Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK]) : $this->generateActionButtons('admin.testimonial-feedback.record', $testimonial->id, ['view' => Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK]);
                $rating = '';
                for ($i = 0; $i < $testimonial->rating; $i++) { $rating .= '★'; }

                if(isset($testimonial->emp_id)){ $employee_name = $testimonial->employee->name." ".$testimonial->employee->last_name; }else{ $employee_name = '-----'; }
                if(isset($testimonial->campaign_id)){ $campaign_name = $testimonial->campaign->campaign_name; }else{ $campaign_name = '-----'; }

                $temp = [
                    $testimonial->created_at->format('Y-m-d h:i A'),
                    $testimonial->vendorCompany->company_name,
                    $testimonial->user->name." ".$testimonial->user->last_name,
                    $employee_name,
                    $campaign_name,
                    $testimonial->product->product_name,
                    $testimonial->subproduct->subproduct_name,
                    $testimonial->response_type,
                    $testimonial->input_source,
                    $rating,
                    $viewbtn,
                    $this->generateActionButtons('admin.testimonial-feedback', $testimonial->id, ['view' => false, 'edit' => Actions::EDIT_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'delete' => false])
                ];
                array_push($data, $temp);
            }
        }elseif($status == 'reject') {
            foreach ($rows as $testimonial) {

                $viewbtn = ($testimonial->response_type == 'Questionnaire') ? $this->generateActionButtons('admin.testimonial-feedback.questionnaire', $testimonial->id, ['view' => Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK]) : $this->generateActionButtons('admin.testimonial-feedback.record', $testimonial->id, ['view' => Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK]);

                if(isset($testimonial->emp_id)){ $employee_name = $testimonial->employee->name." ".$testimonial->employee->last_name; }else{ $employee_name = '-----'; }
                if(isset($testimonial->campaign_id)){ $campaign_name = $testimonial->campaign->campaign_name; }else{ $campaign_name = '-----'; }

                $temp = [
                    $testimonial->created_at->format('Y-m-d h:i A'),
                    $testimonial->vendorCompany->company_name,
                    $testimonial->user->name." ".$testimonial->user->last_name,
                    $employee_name,
                    $campaign_name,
                    $testimonial->product->product_name,
                    $testimonial->subproduct->subproduct_name,
                    $testimonial->response_type,
                    $testimonial->input_source,
                    $testimonial->reject_reason,
                    $viewbtn,
                    $this->generateActionButtons('admin.testimonial-feedback', $testimonial->id, ['view' => false, 'edit' => Actions::EDIT_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'delete' => false])
                ];
                array_push($data, $temp);
            }
        }elseif($status == 'pending') {
            foreach ($rows as $testimonial) {

                $viewbtn = ($testimonial->response_type == 'Questionnaire') ? $this->generateActionButtons('admin.testimonial-feedback.questionnaire', $testimonial->id, ['view' => Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK]) : $this->generateActionButtons('admin.testimonial-feedback.record', $testimonial->id, ['view' => Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK]);

                if(isset($testimonial->emp_id)){ $employee_name = $testimonial->employee->name." ".$testimonial->employee->last_name; }else{ $employee_name = '-----'; }
                if(isset($testimonial->campaign_id)){ $campaign_name = $testimonial->campaign->campaign_name; }else{ $campaign_name = '-----'; }

                $temp = [
                    $testimonial->created_at->format('Y-m-d h:i A'),
                    $testimonial->vendorCompany->company_name,
                    $testimonial->user->name." ".$testimonial->user->last_name,
                    $employee_name,
                    $campaign_name,
                    $testimonial->product->product_name,
                    $testimonial->subproduct->subproduct_name,
                    $testimonial->response_type,
                    $testimonial->input_source,
                    $testimonial->created_at->diffInDays() . ' Days',
                    $viewbtn,
                    $this->generateActionButtons('admin.testimonial-feedback', $testimonial->id, ['view' => false, 'edit' => Actions::EDIT_VENDOR_WISE_TESTIMONIAL_FEEDBACK, 'delete' => false])
                ];
                array_push($data, $temp);
            }
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Response::where('type', '=', $type)->where('status', '=', $status)->count(); // count of all the records in the database table

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
}