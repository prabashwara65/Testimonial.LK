<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Action;
use DateTime;
use Carbon\Carbon;
use DataTables;

use App\Models\Incentive;
use App\Models\Response;

use App\Http\Constants\Actions;

class IncentiveService extends MainService
{
    public function getDataTable($status, $searchData)
    {
        if($status == 'paid') {
            $incentive = Incentive::where('vendor_company_id', auth()->user()->vendor_company_id)
                ->whereNotNull('incentive_amount')
                ->whereNotNull('paid_date')
                ->whereBetween('paid_date', [$searchData['start_date'], $searchData['end_date']])
                ->get();

            return DataTables::of($incentive)
                ->addColumn('emp_id', function ($incentive) {
                    return $incentive->vendor->emp_id;
                })
                ->addColumn('emp_name', function ($incentive) {
                    return $incentive->vendor->name." ".$incentive->vendor->last_name;
                })
                ->addColumn('branch', function ($incentive) {
                    return $incentive->vendor->branch->branch_code." - ".$incentive->vendor->branch->name;
                })
                ->addColumn('nic', function ($incentive) {
                    return $incentive->vendor->nic;
                })
                ->addColumn('bank_account', function ($incentive) {
                    return $incentive->vendor->bank_account;
                })
                ->editColumn('incentive_amount', '{{ ($incentive_amount) ? "Rs. " . $incentive_amount : "" }}')
                ->addColumn('actions', function ($incentive) {
                    return $this->generateActionButtons('vendor.incentives', $incentive->id, ['view' => false, 'edit' => false, 'delete' => Actions::EDIT_TARGETS]);
                })
                ->rawColumns(['actions'])
                ->make(true);

        } elseif($status == 'reject') {
            $incentive = Incentive::where('vendor_company_id', auth()->user()->vendor_company_id)
                ->whereNotNull('incentive_amount')
                ->whereNotNull('reject_date')
                ->whereBetween('reject_date', [$searchData['start_date'], $searchData['end_date']])
                ->get();

            return DataTables::of($incentive)
                ->addColumn('emp_id', function ($incentive) {
                    return $incentive->vendor->emp_id;
                })
                ->addColumn('emp_name', function ($incentive) {
                    return $incentive->vendor->name." ".$incentive->vendor->last_name;
                })
                ->addColumn('branch', function ($incentive) {
                    return $incentive->vendor->branch->branch_code." - ".$incentive->vendor->branch->name;
                })
                ->addColumn('nic', function ($incentive) {
                    return $incentive->vendor->nic;
                })
                ->addColumn('bank_account', function ($incentive) {
                    return $incentive->vendor->bank_account;
                })
                ->editColumn('incentive_amount', '{{ ($incentive_amount) ? "Rs. " . $incentive_amount : "" }}')
                ->addColumn('actions', function ($incentive) {
                    return $this->generateActionButtons('vendor.incentives', $incentive->id, ['view' => false, 'edit' => false, 'delete' => Actions::EDIT_TARGETS]);
                })
                ->rawColumns(['actions'])
                ->make(true);

        } elseif($status == 'pending') {
            $incentive = Incentive::where('vendor_company_id', auth()->user()->vendor_company_id)
                ->whereNull('incentive_amount')
                ->whereNull('paid_date')
                ->whereHas('campaign', function($q) use ($searchData) {
                    $q->whereBetween('end_date', [$searchData['start_date'], $searchData['end_date']]);
                })
                ->get();

            return DataTables::of($incentive)
                ->addColumn('emp_id', function ($incentive) {
                    return $incentive->vendor->emp_id;
                })
                ->addColumn('emp_name', function ($incentive) {
                    return $incentive->vendor->name." ".$incentive->vendor->last_name;
                })
                ->addColumn('branch', function ($incentive) {
                    return $incentive->vendor->branch->branch_code." - ".$incentive->vendor->branch->name;
                })
                ->addColumn('campaign', function ($incentive) {
                    return $incentive->campaign->campaign_name;
                })
                ->addColumn('target', function ($incentive) {
                    return $incentive->campaign->target->target_name;
                })
                ->addColumn('common_target', function ($incentive) {
                    $responseCount = $this->responseCount($incentive);
                    return $responseCount['commonCount'];
                })
                ->addColumn('video_target', function ($incentive) {
                    $responseCount = $this->responseCount($incentive);
                    return $responseCount['videoCount'];
                })
                ->addColumn('audio_target', function ($incentive) {
                    $responseCount = $this->responseCount($incentive);
                    return $responseCount['audioCount'];
                })
                ->addColumn('image_target', function ($incentive) {
                    $responseCount = $this->responseCount($incentive);
                    return $responseCount['imageCount'];
                })
                ->addColumn('text_target', function ($incentive) {
                    $responseCount = $this->responseCount($incentive);
                    return $responseCount['textCount'];
                })
                ->addColumn('incentive_rate', function ($incentive) {
                    return $incentive->campaign->incentive_rate;
                })
                ->addColumn('incentive_amount', function ($incentive) {
                    $responseCount = $this->responseCount($incentive);
                    return "Rs. " . $responseCount['amount'];
                })
                ->addColumn('end_date', function ($incentive) {
                    return $incentive->campaign->end_date;
                })
                ->addColumn('actions', function ($incentive) {
                    return $this->generateActionButtons('vendor.incentives', $incentive->id, ['view' => false, 'edit' => Actions::EDIT_TARGETS, 'delete' => false]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    private function responseCount($incentive)
    {
        $totalRecord = 0;

        // Special Target
        $recordTypes = ['video', 'audio', 'image', 'text'];
        foreach($recordTypes as $recordType)
        {
            $record = Response::where('emp_id', $incentive->vendor->id)
            ->where('campaign_id', $incentive->campaign->id)
            ->where('response_type', 'Record')
            ->whereBetween('created_at', [$incentive->campaign->start_date, $incentive->campaign->end_date])
            ->whereHas('responseRecord', function ($q) use ($recordType) {
                $q->whereNotNull($recordType);
            })->count();

            if($incentive->campaign->target->$recordType) {
                $data[$recordType.'Count'] = $record . '/' . $incentive->campaign->target->$recordType;
            } else {
                $data[$recordType.'Count'] = '';
            }

            $totalRecord += $record;
        }

        // Common Target
        $questionnaire = Response::where('emp_id', $incentive->vendor->id)
            ->where('campaign_id', $incentive->campaign->id)
            ->where('response_type', 'Questionnaire')
            ->whereBetween('created_at', [$incentive->campaign->start_date, $incentive->campaign->end_date])->count();

        if($incentive->campaign->target->target) {
            $data['commonCount'] = ($totalRecord + $questionnaire) . '/' . $incentive->campaign->target->target;

            // Amount Calculation
            if($incentive->campaign->incentive_rate)
            {
                $data['amount'] = ($totalRecord + $questionnaire) * $incentive->campaign->incentive_rate;
            } else {
                $data['amount'] = 0;
            }

        } else {
            $data['commonCount'] = '';

            // Amount Calculation
            if($incentive->campaign->incentive_rate)
            {
                $data['amount'] = $totalRecord * $incentive->campaign->incentive_rate;
            } else {
                $data['amount'] = 0;
            }
        }

        return $data;
    }

    public function markAsPaid($request, $id)
    {
        try {
            $incentive = Incentive::find($id);
            $cal = $this->responseCount($incentive);

            $input['incentive_amount'] = $cal['amount'];

            if($request->status == 'Paid') {
                $input['paid_date'] = $request->date;
            } else {
                $input['reject_date'] = $request->date;
            }

            $incentive->update($input);

            return ['status' => 'success', 'incentive' => $incentive];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function markAsUnpaid($id)
    {
        try {
            $incentive = Incentive::find($id);

            $input['incentive_amount'] = Null;
            $input['paid_date'] = Null;

            $incentive->update($input);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue, $filterForm, $status)
    {
        try {

            $title = 'Incentive - ' . ucfirst($status);

            if($status == 'paid') {

                $model = Incentive::where('incentives.vendor_company_id', auth()->user()->vendor_company_id)
                    ->whereNotNull('incentives.incentive_amount')
                    ->whereNotNull('incentives.paid_date')
                    ->leftjoin('vendors', 'incentives.vendor_id', 'vendors.id')
                    ->leftjoin('branches', 'vendors.branch_id', 'branches.id');

                if (!empty($searchValue)) {
                    $model = $this->search($model, $columns, $searchValue);
                }

                $columnHeaders = array('Emp ID', 'Employee Name', 'Branch', 'NIC', 'Bank Acc No', 'Incentive Amount', 'Paid Date');
                $tableColumns = array('emp_id', 'emp_name', 'branch', 'nic', 'bank_account', 'incentive_amount', 'paid_date');

                $select = array('vendors.emp_id', 'vendors.nic', 'vendors.bank_account', 'incentive_amount', 'paid_date');

                array_push( $select,
                    DB::raw("CONCAT(vendors.name, ' ', vendors.last_name) AS 'emp_name'"),
                    DB::raw("CONCAT(branches.branch_code, ' - ', branches.name) AS 'branch'")
                );

                $model = $model->orderBy('vendors.emp_id', 'ASC')->get($select);

            } elseif($status == 'reject') {

                $model = Incentive::where('incentives.vendor_company_id', auth()->user()->vendor_company_id)
                    ->whereNotNull('incentives.incentive_amount')
                    ->whereNotNull('incentives.reject_date')
                    ->leftjoin('vendors', 'incentives.vendor_id', 'vendors.id')
                    ->leftjoin('branches', 'vendors.branch_id', 'branches.id');

                if (!empty($searchValue)) {
                    $model = $this->search($model, $columns, $searchValue);
                }

                $columnHeaders = array('Emp ID', 'Employee Name', 'Branch', 'NIC', 'Bank Acc No', 'Incentive Amount', 'Reject Date');
                $tableColumns = array('emp_id', 'emp_name', 'branch', 'nic', 'bank_account', 'incentive_amount', 'reject_date');

                $select = array('vendors.emp_id', 'vendors.nic', 'vendors.bank_account', 'incentive_amount', 'reject_date');

                array_push( $select,
                    DB::raw("CONCAT(vendors.name, ' ', vendors.last_name) AS 'emp_name'"),
                    DB::raw("CONCAT(branches.branch_code, ' - ', branches.name) AS 'branch'")
                );

                $model = $model->orderBy('vendors.emp_id', 'ASC')->get($select);

            } elseif($status == 'pending') {

                $model = Incentive::where('incentives.vendor_company_id', auth()->user()->vendor_company_id)
                    ->whereNull('incentives.incentive_amount')
                    ->whereNull('incentives.paid_date')
                    ->leftjoin('vendors', 'incentives.vendor_id', 'vendors.id')
                    ->leftjoin('branches', 'vendors.branch_id', 'branches.id')
                    ->leftjoin('campaigns', 'incentives.campaign_id', 'campaigns.id')
                    ->leftjoin('targets', 'campaigns.target_id', 'targets.id');

                if (!empty($searchValue)) {
                    $model = $this->search($model, $columns, $searchValue);
                }

                $columnHeaders = array('Emp ID', 'Employee Name', 'Branch', 'Campaign Name', 'Target Type', 'Common Target', 'Video Target', 'Audio Target', 'Image Target', 'Text Target', 'Incentive Rate', 'Incentive Amount', 'End Date');
                $tableColumns = array('emp_id', 'emp_name', 'branch', 'campaign_name', 'target_name', 'common_count', 'video_count', 'audio_count', 'image_count', 'text_count', 'incentive_rate', 'incentive_amount', 'end_date');

                $select = array('incentives.*','vendors.emp_id', 'campaigns.campaign_name', 'targets.target_name', 'campaigns.incentive_rate', 'campaigns.end_date');

                array_push( $select,
                    DB::raw("CONCAT(vendors.name, ' ', vendors.last_name) AS 'emp_name'"),
                    DB::raw("CONCAT(branches.branch_code, ' - ', branches.name) AS 'branch'")
                );

                $model = $model->orderBy('vendors.emp_id', 'ASC')->get($select);

                foreach($model as $incentive){
                    $responseCount = $this->responseCount($incentive);

                    $incentive['common_count'] = $responseCount['commonCount'];
                    $incentive['video_count'] = $responseCount['videoCount'];
                    $incentive['audio_count'] = $responseCount['audioCount'];
                    $incentive['image_count'] = $responseCount['imageCount'];
                    $incentive['text_count'] = $responseCount['textCount'];
                    $incentive['incentive_amount'] = 'Rs. '.$responseCount['amount'];
                }
            }

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
