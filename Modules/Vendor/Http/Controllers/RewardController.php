<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\RewardRequest;

use Modules\Vendor\Http\Services\RewardService;

use App\Http\Constants\Actions;


class RewardController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Rewards";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Exp. Date' => 'rewards.date',
        'Reward ID' => 'rewards.reward_code',
        'Reward Type' => 'rewards.reward_type',
        'Discount (Price/Percentage)' => 'rewards.discount',
        'Gift' => 'rewards.gift',
        'Status' => 'status',
        'Actions@no-sort@' => 'id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_REWARDS, 'vendor');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_REWARDS, 'vendor');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_REWARDS, 'vendor');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['export_route'] = "rewards/export";

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = 'vendor.rewards.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.rewards.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_REWARDS, 'vendor');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];
            $columns = $this->columns;

            $orderBy = '';
            $orderDirection = '';
            if (isset($input['order'])) {
                $orderBy = $this->getOrderByColumn($columns, $input['order'][0]['column']);
                $orderDirection = $input['order'][0]['dir'];
            }

            $data = $this->rewardService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkPermissionRedirect(Actions::CREATE_REWARDS, 'vendor');

        $view = View::make('vendor::rewards.create')->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RewardRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_REWARDS, 'vendor');

            $result = $this->rewardService->createReward($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_REWARDS, $result['reward']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Reward created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_REWARDS, 'vendor');

        $data['reward'] = $this->rewardService->getReward($id);

        $view = View::make('vendor::rewards.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RewardRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_REWARDS, 'vendor');

            $result = $this->rewardService->updateReward($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_REWARDS, $result['reward']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Reward updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->checkPermissionRedirect(Actions::DELETE_REWARDS, 'vendor');

            $result = $this->rewardService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_REWARDS, $id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Reward deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

    public function changeStatus(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_REWARDS, 'vendor');

            $result = $this->rewardService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_REWARDS, $result['reward']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Reward status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

    public function export($type = null, $searchValue = null)
    {
        try {
            $columns = $this->columns;

            return $this->rewardService->export($type, $columns, $searchValue);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
