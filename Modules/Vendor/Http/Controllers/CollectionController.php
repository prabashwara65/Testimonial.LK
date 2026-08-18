<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Vendor\Http\Exports\ProductExport;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Response;
use App\Models\ResponseQuestion;
use App\Models\ResponseRecord;

use Modules\Vendor\Http\Services\CollectionService;


class CollectionController extends Controller
{
    /** @var array The names and relevent property name of columns in the table */
    private $columns = [
        'Date/Time' => 'created_at',
        'NIC' => 'nic',
        'Customer Name' => 'customer_name',
        'Type' => 'type',
        'Campaign' => 'campaign',
        'Product Name' => 'product_name',
        'Subproduct Name' => 'subproduct_name',
        'Rating' => 'rating',
        'Input Source' => 'input_source',
        'Status' => 'status',
        'Details@no-sort@' => 'details'
    ];

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
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
            'url' => 'collection.get-data',
            'columns' => $this->columns
        ];

        return view('vendor::salesrep.collection.index', ['dataTable' => $dataTable]);
    }

    public function getData(Request $request)
    {
        try {
            return $this->collectionService->getDataTable();
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

            $view = View::make('vendor::salesrep.collection.single', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'sm');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
