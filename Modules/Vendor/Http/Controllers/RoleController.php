<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\RoleRequest;

use Spatie\Permission\Models\Permission;

use Modules\Vendor\Http\Services\RoleService;

use App\Http\Constants\Actions;

class RoleController extends Controller
{

    /** @var string Title shown at top left of the page */
    private $pageTitle = "Roles";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Role' => 'roles.name',
        'Permissions@no-sort@' => 'permissions',
        'Actions@no-sort@' => 'id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->checkPermissionRedirect(Actions::VIEW_ROLES, 'vendor');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_ROLES, 'vendor');
        $data['editPermission'] =$this->checkHasPermission(Actions::EDIT_ROLES, 'vendor');

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['deletePermission'] = false;

        $data['scripts'] = [];
        $data['addRoute'] = 'vendor.roles.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.roles.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_ROLES, 'vendor');

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

            $data = $this->roleService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

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
        $this->checkPermissionRedirect(Actions::CREATE_ROLES, 'vendor');

        //Get all roles and pass it to the view
        $permissions = Permission::where('guard_name', '=', 'vendor')->get();
        
        $view = View::make('vendor::roles.create', ['permissions' => $permissions])->render();
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
    public function store(RoleRequest $request) {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_ROLES, 'vendor');

            $result = $this->roleService->createRole($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_ROLES, $result['role']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Role created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        $this->checkPermissionRedirect(Actions::EDIT_ROLES, 'vendor');

        $data['role'] = $this->roleService->getRole($id);
        $data['permissions'] = Permission::where('guard_name', '=', 'vendor')->get();

        $view = View::make('vendor::roles.edit', $data)->render();
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
    public function update(RoleRequest $request, $id) {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_ROLES, 'vendor');

            $result = $this->roleService->updateRole($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_ROLES, $result['role']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Role updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::DELETE_ROLES);

            $result = $this->roleService->deleteRole($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_ROLES, $id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Role deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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