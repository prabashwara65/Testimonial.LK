<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Reward;

use Modules\Vendor\Http\Repositories\RewardRepository;

use App\Http\Constants\Actions;

class RewardService extends MainService
{
    protected $rewardRepository;

    public function __construct(
        RewardRepository $rewardRepository)
    {
        $this->rewardRepository = $rewardRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $reward = Reward::where('vendor_company_id', auth()->user()->vendor_company_id);
        // * customize end

        if (!empty($search)) {
            $reward = $this->search($reward, $columns, $search);
        }
        if (!empty($orderBy)) {
            $reward->orderBy($orderBy, $orderDirection);
        } else {
            $reward->orderBy('rewards.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $reward->get();
        $count = count($rows);

        // limit the results for pagination
        $reward->offset($offset)->limit($limit);
        $rows = $reward->get();

        $data = [];

        foreach ($rows as $reward) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$reward->id.'" onchange="changeUserStatus(event.target, '. $reward->id .', \''. route('vendor.rewards.status') .'\')" ';
            $toggle .= ($reward->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$reward->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $reward->date,
                $reward->reward_code,
                ucfirst($reward->reward_type),
                $reward->discount,
                $reward->gift,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.rewards', $reward->id, ['view' => false, 'edit' => Actions::EDIT_REWARDS, 'delete' => Actions::DELETE_REWARDS])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Reward::count(); // count of all the records in the database table

        return $out;
    }

    public function createReward($request)
    {
        try {
            $input = $request->only(['date', 'reward_code', 'reward_type', 'discount', 'gift']);
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;

            $reward = $this->rewardRepository->create($input);

            return ['status' => 'success', 'reward' => $reward];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateReward($request, $id)
    {
        try {
            $reward = $this->rewardRepository->show($id); //Get reward specified by id

            $input = $request->only(['date', 'reward_code', 'reward_type', 'discount', 'gift']);
            $reward->fill($input)->save();

            return ['status' => 'success', 'reward' => $reward];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getReward($editId)
    {
        $reward = $this->rewardRepository->show($editId);

        $temp['id'] = $reward->id;
        $temp['date'] = $reward->date;
        $temp['reward_code'] = $reward->reward_code;
        $temp['reward_type'] = $reward->reward_type;
        $temp['discount'] = $reward->discount;
        $temp['gift'] = $reward->gift;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $this->rewardRepository->delete($id);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStatus($request)
    {
        try {
            $reward = $this->rewardRepository->show($request->id); //Get reward specified by id

            $input = $request->only(['status']);
            $reward->fill($input)->save();

            return ['status' => 'success', 'reward' => $reward];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Rewards';

            $columnHeaders = array('Date', 'Reward ID', 'Reward Type', 'Discount Price', 'Gift', 'Status');
            $tableColumns = array('date', 'reward_code', 'reward_type', 'discount', 'gift', 'status');

            $model = Reward::where('rewards.vendor_company_id', auth()->user()->vendor_company_id);

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = $tableColumns;
            array_push( $select,
                        DB::raw("(CASE WHEN rewards.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
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
        });

        return $model;
    }

}