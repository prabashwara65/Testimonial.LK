<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\ActionLogRequest;

use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\ActionLog;

use Modules\Admin\Http\Services\ActionLogService;

use App\Http\Constants\Actions;


class ActionLogController extends Controller
{

    /** @var string Title shown at top left of the page */
    private $pageTitle = "Action Log";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'User' => 'user',
        'IP' => 'ip',
        'Action' => 'action',
        'Date' => 'created_at',
        'Actions@no-sort@' => 'id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(ActionLogService $actionLogService)
    {
        $this->actionLogService = $actionLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->checkPermissionRedirect(Actions::VIEW_ACTION_LOG, 'admin');

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['viewSingle'] = true;

        $data['getData'] = ['url' => 'admin.action-log.get-data', 'holder' => 'table-holder'];

        $sectionData['users'] = User::get();
        $sectionData['permissions'] = Permission::get();

        $data['table_before_section'] = View::make('admin::action_log.filter-form', $sectionData)->render();

        return view('common/table-holder', $data);
    }

    public function getData(ActionLogRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_ACTION_LOG, 'admin');

            $input = $request->all();

            $offset = $input['start'];
            $limit = $input['length'];
            $search = $input['search']['value'];
            $columns = $this->columns;

            $filterForm = $input['form'];

            $orderBy = '';
            $orderDirection = '';
            if (isset($input['order'])) {
                $orderBy = $this->getOrderByColumn($columns, $input['order'][0]['column']);
                $orderDirection = $input['order'][0]['dir'];
            }

            $data = $this->actionLogService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm);

            $data['draw'] =  $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_ACTION_LOG, 'admin');

            //Get all users and pass it to the view
            $data['log'] = ActionLog::findOrFail($id);

            $view = View::make('admin::action_log/single', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (ModelNotFoundException $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => 'No action log record found', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::VIEW_ACTION_LOG, 'admin');

            ActionLog::where('id', $id)->update(['comments' => $request->comments]);

            $outPutArray = array('status' => 'success', 'message' => 'Comment added successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}