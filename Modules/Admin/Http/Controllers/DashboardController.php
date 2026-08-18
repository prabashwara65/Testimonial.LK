<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Response;
use App\Models\VendorCompany;

use Modules\Admin\Http\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request) {
        $data['title'] = 'Dashboard';

        $data['vendor_companies'] = VendorCompany::where('status', 1)->get();

        $data['total_vendors'] = $data['vendor_companies']->count();

        $result = Response::get();
        $data['total_approved_testimonial'] = $result->where('type', 1)->where('status', 'approved')->count();
        $data['total_approved_feedback'] = $result->where('type', 2)->where('status', 'approved')->count();



        // Vendor Wise Area
        if ($request->has('vendor_company') && $data['vendor_companies']->contains('id', $request->vendor_company)) {
            $vendor_company_id = $request->vendor_company;
            session()->put('vendor_company_id', $vendor_company_id);
        } elseif (session()->get('vendor_company_id') && $data['vendor_companies']->contains('id', session()->get('vendor_company_id'))) {
            $vendor_company_id = session()->get('vendor_company_id');
        } else {
            $vendor_company_id = optional($data['vendor_companies']->first())->id;

            if ($vendor_company_id) {
                session()->put('vendor_company_id', $vendor_company_id);
            } else {
                session()->forget('vendor_company_id');
            }
        }

        $data['vendor_company_id'] = $vendor_company_id;

        $result = Response::where('vendor_company_id', $vendor_company_id)->get();

        $data['all_testimonial'] = $result->where('type', 1)->count();
        $data['approved_testimonial'] = $result->where('type', 1)->where('status', 'approved')->count();
        $data['reject_testimonial'] = $result->where('type', 1)->where('status', 'reject')->count();
        $data['pending_testimonial'] = $result->where('type', 1)->where('status', 'pending')->count();

        $data['all_feedback'] = $result->where('type', 2)->count();
        $data['approved_feedback'] = $result->where('type', 2)->where('status', 'approved')->count();
        $data['reject_feedback'] = $result->where('type', 2)->where('status', 'reject')->count();
        $data['pending_feedback'] = $result->where('type', 2)->where('status', 'pending')->count();

        $data['testimonialCount'] = $this->dashboardService->responseCount($vendor_company_id,1);
        $data['feedbackCount'] = $this->dashboardService->responseCount($vendor_company_id,2);

        $data['testimonialRating'] = $this->dashboardService->responseRating($vendor_company_id,1);
        $data['feedbackRating'] = $this->dashboardService->responseRating($vendor_company_id,2);


        return view('admin::dashboard.dashboard', $data);
    }
}
