<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\UserRequest;

use Spatie\Permission\Models\Role;
use App\Models\Region;

use Modules\Admin\Http\Services\UserService;

use App\Http\Constants\Actions;


class UserController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Users";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'ID' => 'admins.id',
        'EMP ID' => 'admins.emp_id',
        'Name' => 'admins.name',
        'NIC' => 'admins.nic',
        'Email' => 'admins.email',
        'Mobile' => 'admins.mobile',
        'Address' => 'admins.address',
        'Country@no-sort@' => '',
        'Region@no-sort@' => '',
        'Designation' => 'admins.designation',
        'Department' => 'admins.department',
        'User Role@no-sort@' => '',
        'Status@no-sort@' => 'admins.status',
        'Actions@no-sort@' => 'admins.id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_USERS, 'admin');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_USERS, 'admin');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_USERS, 'admin');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['scripts'] = ['user_management.js'];
        $data['addRoute'] = 'admin.users.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'admin.users.get-data', 'holder' => 'table-holder'];

        return view('common.table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_USERS, 'admin');

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

            $data = $this->userService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

            $data['draw'] = $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->checkPermissionRedirect(Actions::CREATE_USERS, 'admin');

        if (!auth()->user()->hasRole('Super Admin')) {
            $data['roles'] = Role::where('guard_name', '=', 'admin')->where('name', '!=', 'Super Admin')->get();
        }
        else {
            $data['roles'] = Role::where('guard_name', '=', 'admin')->get();
        }

        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::users.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     * @param UserRequest $request
     * @return Renderable
     */
    public function store(UserRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_USERS, 'admin');

            $result = $this->userService->createUser($request);

            if ($result['status'] == 'success') {
                // remove token and password from logged request parameters
                $this->logAction(Actions::CREATE_USERS, $result['user']->id, json_encode(request()->except(['password', 'password_confirmation'])), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'User created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_USERS, 'admin');

        $data['user'] = $this->userService->getUser($id); //Get user with specified id
        if (!auth()->user()->hasRole('Super Admin')) {
            $data['roles'] = Role::where('guard_name', '=', 'admin')->where('name', '!=', 'Super Admin')->get();
        }
        else {
            $data['roles'] = Role::where('guard_name', '=', 'admin')->get();
        }
        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::users.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_USERS, 'admin');

            $result = $this->userService->updateUser($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_USERS, $result['user']->id, json_encode(request()->except(['password', 'password_confirmation'])), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'User updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        //
    }

    public function changeStatus(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_USERS, 'admin');

            $result = $this->userService->updateStatus($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_USERS, $result['user']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'User status updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
}
