<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\ProvinceRequest;

use App\Models\Region;
use App\Models\Country;

use Modules\Admin\Http\Services\ProvinceService;

use App\Http\Constants\Actions;


class ProvinceController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Provinces";

    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'ID@no-sort@' => 'provinces.id',
        'Province Name@no-sort@' => 'provinces.province',
        'Country Name@no-sort@' => '',
        'Region Name@no-sort@' => '',
        'Actions@no-sort@' => 'provinces.id' // add the primary key to be used to identify the row when editing or deleting here
    ];

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_PROVINCES, 'admin');

        $data['addPermission'] = $this->checkHasPermission(Actions::CREATE_PROVINCES, 'admin');
        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_PROVINCES, 'admin');
        $data['deletePermission'] = false;

        $data['title'] = $this->pageTitle;
        $data['columns'] = $this->columns;

        $data['scripts'] = [];
        $data['addRoute'] = 'admin.provinces.create';

        $data['viewSingle'] = false;

        $data['getData'] = ['url' => 'admin.provinces.get-data', 'holder' => 'table-holder'];

        return view('common/table-holder', $data);
    }

    public function getData(Request $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::VIEW_PROVINCES, 'admin');

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

            $data = $this->provinceService->getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection);

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
        $this->checkPermissionRedirect(Actions::CREATE_PROVINCES, 'admin');

        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::provinces.create', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
        return $outPutArray;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProvinceRequest $request)
    {
        try {
            $this->checkPermissionRedirect(Actions::CREATE_PROVINCES, 'admin');

            $result = $this->provinceService->createProvince($request);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::CREATE_PROVINCES, $result['province']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Province created successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
        $this->checkPermissionRedirect(Actions::EDIT_PROVINCES, 'admin');

        $data['province'] = $this->provinceService->getProvince($id);

        $data['regions'] = Region::get();
        $data['countries'] = Country::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('admin::provinces.edit', $data)->render();
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
    public function update(ProvinceRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_PROVINCES, 'admin');

            $result = $this->provinceService->updateProvince($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_PROVINCES, $result['province']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Province updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
            $this->checkPermissionRedirect(Actions::DELETE_PROVINCES, 'admin');

            $result = $this->provinceService->delete($id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::DELETE_PROVINCES, $id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Province deleted successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
