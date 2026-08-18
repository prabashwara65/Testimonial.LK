<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Campaign;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Subproduct;
use App\Models\Vendor;

use Modules\Vendor\Http\Repositories\CampaignRepository;

use App\Http\Constants\Actions;
use App\Models\Incentive;

class CampaignService extends MainService
{
    protected $campaignRepository;

    public function __construct(
        CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm)
    {
        $campaign = Campaign::where('vendor_company_id', auth()->user()->vendor_company_id)
                        ->where('start_date', '<=', $filterForm['end_date'])
                        ->where('end_date', '>=', $filterForm['start_date']);

        if (!empty($search)) {
            $campaign = $this->search($campaign, $columns, $search);
        }
        if (!empty($orderBy)) {
            $campaign->orderBy($orderBy, $orderDirection);
        } else {
            $campaign->orderBy('campaigns.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $campaign->get();
        $count = count($rows);

        // limit the results for pagination
        $campaign->offset($offset)->limit($limit);
        $rows = $campaign->get();

        $data = [];

        foreach ($rows as $campaign) {
            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$campaign->id.'" onchange="changeUserStatus(event.target, '. $campaign->id .', \''. route('vendor.campaigns.status') .'\')" ';
            $toggle .= ($campaign->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$campaign->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $campaign->campaign_name,
                $campaign->campaign_type,
                $campaign->target->target_name,
                $campaign->response_type,
                $campaign->incentive_rate,
                $campaign->start_date,
                $campaign->end_date,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.campaigns', $campaign->id, ['view' => false, 'edit' => Actions::EDIT_CAMPAIGNS, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Campaign::count(); // count of all the records in the database table

        return $out;
    }

    public function createCampaign($request)
    {
        try {
            $campaign = new Campaign();
            $campaign->campaign_name = $request['campaign_name'];
            $campaign->campaign_type = $request['campaign_type'];
            $campaign->target_id = $request['target_id'];
            $campaign->response_type = $request['response_type'];
            $campaign->incentive_rate = $request['incentive_rate'];
            $campaign->start_date = $request['start_date'];
            $campaign->end_date = $request['end_date'];
            $campaign->vendor_company_id = auth()->user()->vendor_company_id;
            $campaign->status = 1;
            $campaign->save();

            if (!empty($request['branches'])) {
                foreach ($request['branches'] as $branch) {
                    $branch = Branch::find($branch);
                    $campaign->branches()->save($branch);
                }
            }

            if (!empty($request['employees'])) {
                foreach ($request['employees'] as $employeeId) {
                    $employee = Vendor::find($employeeId);
                    $campaign->employees()->save($employee);
                    $campaign->linkIncentives()->save($employee, ['vendor_company_id' => auth()->user()->vendor_company_id]);
                }
            }

            if (!empty($request['product_id'])) {
                foreach ($request['product_id'] as $key => $q) {
                    $product = Product::find($request['product_id'][$key]);
                    $campaign->products()->save($product);

                    foreach ($request['subproduct_id'][$key] as $subproduct) {
                        $subproduct = Subproduct::find($subproduct);
                        $campaign->subproducts()->save($subproduct);
                    }
                }
            }

            return ['status' => 'success', 'campaign' => $campaign];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateCampaign($request, $id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->campaign_name = $request['campaign_name'];
            $campaign->campaign_type = $request['campaign_type'];
            $campaign->target_id = $request['target_id'];
            $campaign->response_type = $request['response_type'];
            $campaign->incentive_rate = $request['incentive_rate'];
            $campaign->start_date = $request['start_date'];
            $campaign->end_date = $request['end_date'];
            $campaign->vendor_company_id = auth()->user()->vendor_company_id;
            $campaign->status = 1;
            $campaign->save();

            $campaign->branches()->detach(); // previous data recode delete
            $campaign->employees()->detach(); // previous data recode delete
            $campaign->products()->detach(); // previous data recode delete
            $campaign->subproducts()->detach(); // previous data recode delete
            $campaign->linkIncentives()->detach(); // previous data recode delete

            if (!empty($request['branches'])) {
                foreach ($request['branches'] as $branch) {
                    $branch = Branch::find($branch);
                    $campaign->branches()->save($branch);
                }
            }

            if (!empty($request['employees'])) {
                foreach ($request['employees'] as $employee) {
                    $employee = Vendor::find($employee);
                    $campaign->employees()->save($employee);
                    $campaign->linkIncentives()->save($employee, ['vendor_company_id' => auth()->user()->vendor_company_id]);
                }
            }

            if (!empty($request['product_id'])) {
                foreach ($request['product_id'] as $key => $q) {
                    $product = Product::find($request['product_id'][$key]);
                    $campaign->products()->save($product);

                    foreach ($request['subproduct_id'][$key] as $subproduct) {
                        $subproduct = Subproduct::find($subproduct);
                        $campaign->subproducts()->save($subproduct);
                    }
                }
            }

            return ['status' => 'success', 'campaign' => $campaign];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getCampaign($editId)
    {
        $campaign = $this->campaignRepository->show($editId);

        $temp['id'] = $campaign->id;
        $temp['campaign_name'] = $campaign->campaign_name;
        $temp['campaign_type'] = $campaign->campaign_type;
        $temp['target_id'] = $campaign->target_id;
        $temp['response_type'] = $campaign->response_type;
        $temp['incentive_rate'] = $campaign->incentive_rate;
        $temp['start_date'] = $campaign->start_date;
        $temp['end_date'] = $campaign->end_date;
        $temp['branches'] = $campaign->branches->pluck('id')->toArray();
        $temp['employees'] = $campaign->employees->pluck('id')->toArray();
        $temp['products'] = $campaign->products->pluck('id')->toArray();
        $temp['subproducts'] = $campaign->subproducts->pluck('id')->toArray();

        return $temp;
    }

    public function updateStatus($request)
    {
        try {
            $campaign = $this->campaignRepository->show($request->id); //Get campaign specified by id

            $input = $request->only(['status']);
            $campaign->fill($input)->save();

            return ['status' => 'success', 'campaign' => $campaign];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue, $filterForm)
    {
        try {

            $title = 'Campaigns';

            $columnHeaders = array('Campaign Name', 'Campaign Type', 'Target Type', 'Response Type', 'Incentive Rate', 'Start Date', 'End Date', 'Status');
            $tableColumns = array('campaign_name', 'campaign_type', 'target_type', 'response_type', 'incentive_rate', 'start_date', 'end_date', 'status');

            $model = Campaign::where('campaigns.vendor_company_id', auth()->user()->vendor_company_id)
                            ->where('start_date', '<=', $filterForm['end_date'])
                            ->where('end_date', '>=', $filterForm['start_date'])
                            ->join('targets', 'campaigns.target_id', 'targets.id');

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = array('campaigns.campaign_name', 'campaigns.campaign_type', 'targets.target_type', 'campaigns.response_type', 'campaigns.incentive_rate', 'campaigns.start_date', 'campaigns.end_date', 'campaigns.status');
            array_push( $select,
                        DB::raw("targets.target_name AS target_type"),
                        DB::raw("(CASE WHEN campaigns.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
                    );
            $model = $model->get($select);

            return $this->exportReport($type, $searchValue, $title, $columnHeaders, $tableColumns, $model);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function search($model, $columns, $search)
    {
        $model->where(function($query) use ($columns, $search){
            $query = $this->generateWhereLikeQuery($query, $columns, $search);

            $query->orWhereHas('target', function ($q) use ($search){
                $q->where('target_name', 'LIKE', '%'.$search.'%');
            });
        });

        return $model;
    }

}
