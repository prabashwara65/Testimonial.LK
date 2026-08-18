<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Response;
use App\Models\Setting;

use Modules\Vendor\Http\Services\DashboardService;

use App\Http\Constants\Actions;

class DashboardController extends Controller
{
    private $columns = [
        'Date/Time' => 'created_at',
        'Employee Name' => 'employee_name',
        'Customer Name' => 'customer_name',
        'Type' => 'type',
        'Response Type' => 'response_type',
        'Location' => 'geo_address',
        'Details@no-sort@' => 'details'
    ];

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request) {

        $this->checkPermissionRedirect(Actions::DASHBOARD, 'vendor');
        
        //// Map and Table ////

        if($request->has('start_date') && $request->has('end_date')) {
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            session()->put('start_date', $data['start_date']);
            session()->put('end_date', $data['end_date']);
        } elseif(session()->get('start_date') && session()->get('end_date')) {
            $data['start_date'] = session()->get('start_date');
            $data['end_date'] = session()->get('end_date');
        } else {
            $data['start_date'] = date("Y-m-d", strtotime("-1 day"));
            $data['end_date'] = date('Y-m-d');
        }

        $data['mapdata'] = Response::where('vendor_company_id', auth()->user()->vendor_company_id)->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereBetween('created_at', [$data['start_date'], date('Y-m-d H:i:s', strtotime($data['end_date'].'+1 day'))])
            ->get();

        if($request->ajax())
        {
            return $this->dashboardService->getDataTable($data['mapdata']);
        }

        //// Map and Table End ////

        $data['title'] = 'Dashboard';
        $data['scripts'] = ['testimonial.js'];

        $dataTable = [
            'table' => 'dataTable',
            'url' => 'vendor.dashboard',
            'columns' => $this->columns
        ];

        $result = Response::where('vendor_company_id', auth()->user()->vendor_company_id)->get();

        $data['all_testimonial'] = $result->where('type', 1)->count();
        $data['approved_testimonial'] = $result->where('type', 1)->where('status', 'approved')->count();
        $data['reject_testimonial'] = $result->where('type', 1)->where('status', 'reject')->count();
        $data['pending_testimonial'] = $result->where('type', 1)->where('status', 'pending')->count();

        $data['all_feedback'] = $result->where('type', 2)->count();
        $data['approved_feedback'] = $result->where('type', 2)->where('status', 'approved')->count();
        $data['reject_feedback'] = $result->where('type', 2)->where('status', 'reject')->count();
        $data['pending_feedback'] = $result->where('type', 2)->where('status', 'pending')->count();

        $data['testimonialCount'] = $this->dashboardService->responseCount(1);
        $data['feedbackCount'] = $this->dashboardService->responseCount(2);

        $data['testimonialRating'] = $this->dashboardService->responseRating(1);
        $data['feedbackRating'] = $this->dashboardService->responseRating(2);

        return view('vendor::dashboard.dashboard', $data, ['dataTable' => $dataTable]);
    }

    public function show($id)
    {
        try {
            $response = Response::find($id);

            $data['geo_data'] = $response->only('latitude', 'longitude', 'geo_address');

            if($response->response_type == 'Questionnaire') {
                $data['questionnaires'] = $response->responseQuestions;
            }
            else {
                $data['record'] = $response->responseRecord;
            }

            $view = View::make('vendor::testimonial_feedback.single', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'sm');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
