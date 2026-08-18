<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use App\Models\Response;

use App\Http\Services\HistoryService;


class HistoryController extends Controller
{
    private $columns = [
        'Date/Time' => 'created_at',
        'Type' => 'type',
        'Company Name' => 'company_name',
        'Product Name' => 'product_name',
        'Subproduct Name' => 'subproduct_name',
        'Details@no-sort@' => 'details'
    ];

    public function __construct(HistoryService $historyService)
    {
        $this->middleware(['auth', 'verified']);

        $this->historyService = $historyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataTable = [
            'table' => 'dataTable',
            'url' => 'history.get-data',
            'columns' => $this->columns
        ];

        return view('history.index', ['dataTable' => $dataTable]);
    }

    public function getData()
    {
        try {
            return $this->historyService->getDataTable();
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function show($id)
    {
        try {
            $response = Response::find($id);
            if($response->response_type == 'Questionnaire') {
                $data['questionnaires'] = $response->responseQuestions;
            }
            else {
                $data['record'] = $response->responseRecord;
            }

            $view = View::make('history.single', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'sm');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
