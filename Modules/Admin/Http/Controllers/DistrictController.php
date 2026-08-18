<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\DistrictRequest;

use App\Models\Region;
use App\Models\Country;
use App\Models\Province;

use Modules\Admin\Http\Services\DistrictService;

use App\Http\Constants\Actions;


class DistrictController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Districts";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'ID@no-sort@' => 'districts.id',
        'District Name@no-sort@' => 'districts.district',
        'Province Name@no-sort@' => '',
        'Country Name@no-sort@' => '',
        'Region Name@no-sort@' => '',
        'Actions@no-sort@' => 'districts.id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_DISTRICTS, 'admin');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_DISTRICTS, 'admin');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_DISTRICTS, 'admin');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['scripts'] = [];
        $data['addRoute'] = 'admin.districts.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'admin.districts.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_DISTRICTS, 'admin');

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

            $data = $this->districtService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

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
        $this->checkPermissionRedirect(Actions::CREATE_DISTRICTS, 'admin');

        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');
        $data['loadProvincesUrl'] = route('load-provinces');

        $view = View::make('admin::districts.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistrictRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_DISTRICTS, 'admin');

            $result = $this->districtService->createDistrict($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_DISTRICTS, $result['district']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'District created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        $this->checkPermissionRedirect(Actions::EDIT_DISTRICTS, 'admin');

        $data['district'] = $this->districtService->getDistrict($id);

        $data['regions'] = Region::get();
        $data['countries'] = Country::get();
        $data['provinces'] = Province::get();
        $data['loadCountriesUrl'] = route('load-countries');
        $data['loadProvincesUrl'] = route('load-provinces');

        $view = View::make('admin::districts.edit', $data)->render();
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
    public function update(DistrictRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_DISTRICTS, 'admin');

            $result = $this->districtService->updateDistrict($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_DISTRICTS, $result['district']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'District updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::DELETE_DISTRICTS, 'admin');

            $result = $this->districtService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_DISTRICTS, $id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'District deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
