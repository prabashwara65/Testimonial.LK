<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CustomerRequest;

use App\Models\Region;

use Modules\Admin\Http\Services\CustomerService;

use App\Http\Constants\Actions;


class CustomerController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Customers";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Customer Name@no-sort@' => 'users.name',
        'NIC' => 'users.nic',
        'Email@no-sort@' => 'users.email',
        'Mobile@no-sort@' => 'users.mobile',
        'Address' => 'users.address',
        'Country@no-sort@' => '',
        'Region@no-sort@' => '',
        'Actions@no-sort@' => 'users.id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'admin');

        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_CUSTOMERS, 'admin');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['scripts'] = ['user_management.js'];

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'admin.customers.get-data', 'holder' => 'table-holder'];

        return view('common.table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'admin');

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

            $data = $this->customerService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

            $data['draw'] = $input['draw'];

            return $data;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_CUSTOMERS, 'admin');

        $data['customer'] = $this->customerService->getCustomer($id); //Get vendor with specified id
        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::customers.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CustomerRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_CUSTOMERS, 'admin');

            $result = $this->customerService->updateCustomer($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_CUSTOMERS, $result['customer']->id, json_encode(request()->except(['password', 'password_confirmation'])), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Vendor updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
}
