<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use App\Models\Region;

use Modules\Vendor\Http\Services\CustomerService;

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
        'Details@no-sort@' => '',
        'Testimonial@no-sort@' => '',
        'Feedback@no-sort@' => '',
        'Rewards@no-sort@' => '',
        'Assign Reward@no-sort@' => 'users.id' // add the primary key to be used to identify the row when editing or deleting here
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
        $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'vendor');

        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_CUSTOMERS, 'vendor');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['export_route'] = "customers/export";

        $data['scripts'] = ['testimonial.js'];
        $data['addRoute'] = 'vendor.customers.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'vendor.customers.get-data', 'holder' => 'table-holder'];

        return view('common.table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'vendor');

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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'vendor');

        $data['user'] = $this->customerService->getCustomer($id); //Get user with specified id
        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('vendor::customers.customer', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    public function getTestimonial($id)
    {
        $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'vendor');

        $result = $this->customerService->getResponse($id, 1); // 1-Testimonial

        $view = View::make('vendor::customers.response', $result)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    public function getFeedback($id)
    {
        $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'vendor');

        $result = $this->customerService->getResponse($id, 2); // 2-Feedback

        $view = View::make('vendor::customers.response', $result)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    public function getReward($id)
    {
        $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'vendor');

        $result = $this->customerService->getReward($id);

        $view = View::make('vendor::customers.reward', $result)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    public function assignReward($id)
    {
        $this->checkPermissionRedirect(Actions::VIEW_CUSTOMERS, 'vendor');

        $rewards = $this->customerService->assignReward($id);

        $view = View::make('vendor::customers.add-reward', $rewards)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    public function storeReward(Request $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_CUSTOMERS, 'vendor');

            $result = $this->customerService->storeReward($request, $id);

            if ($result['status'] == 'success') {
                // $this->logAction(Actions::EDIT_PRODUCTS, $result['product']->product_id, json_encode(\Illuminate\Support\Facades\Request::all()), json_encode($result), "");

                $outPutArray = array('status' => 'success', 'message' => 'Reward added successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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

            return $this->customerService->export($type, $columns, $searchValue);

        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
