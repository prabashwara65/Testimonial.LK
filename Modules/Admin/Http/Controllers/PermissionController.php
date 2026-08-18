<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\PermissionRequest;

use Spatie\Permission\Models\Role;

use Modules\Admin\Http\Services\RoleService;
use Modules\Admin\Http\Services\PermissionService;

use App\Http\Constants\Actions;


class PermissionController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Permissions";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'ID' => 'permissions.id',
        'Permission' => 'permissions.name',
        'Guard Name' => 'permissions.guard_name',
        'Roles@no-sort@' => '',
        'Actions@no-sort@' => ''
    ];

    public function __construct(PermissionService $permissionService, RoleService $roleService)
    {
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->checkPermissionRedirect(Actions::VIEW_PERMISSIONS, 'admin');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_PERMISSIONS, 'admin');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_PERMISSIONS, 'admin');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['scripts'] = [];
        $data['addRoute'] = 'admin.permissions.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'admin.permissions.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_PERMISSIONS, 'admin');

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

            $data = $this->permissionService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

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
     * @return array
     */
    public function create() {
        $this->checkPermissionRedirect(Actions::CREATE_PERMISSIONS, 'admin');

        $view = View::make('admin::permissions.create')->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(PermissionRequest $request) {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_PERMISSIONS, 'admin');

            $result = $this->permissionService->create($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_PERMISSIONS, $result['permission']->id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Permissions created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
    public function edit(Request $request, $id) {
        $this->checkPermissionRedirect(Actions::EDIT_PERMISSIONS, 'admin');

        $data['permission'] = $this->permissionService->get($id); //Get permissions with specified id

        $view = View::make('admin::permissions.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return array
     * @throws \Exception
     */
    public function update(Request $request, $id) {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_PERMISSIONS, 'admin');

            $result = $this->permissionService->update($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_PERMISSIONS, $result['permission']->id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Permissions updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id) {
        try {
            $this->checkPermissionRedirect(Actions::DELETE_PERMISSIONS, 'admin');

            $result = $this->permissionService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_PERMISSIONS, $id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Permissions deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
    }*/
}