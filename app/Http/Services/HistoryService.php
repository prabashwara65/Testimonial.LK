<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use DataTables;

use App\Models\Response;

class HistoryService extends MainService
{
    public function getDataTable()
    {
        $responses = Response::where('customer_id', auth()->user()->id)->get();

        return DataTables::of($responses)
                    ->editColumn('created_at', '{{ date("Y/m/d - h:i A", strtotime($created_at)) }}')
                    ->editColumn('type', '{{ ($type == "1") ? "Testimonial" : "Feedback" }}')
                    ->addColumn('company_name', function ($response) {
                        return $response->vendorCompany->company_name;
                    })
                    ->addColumn('product_name', function ($response) {
                        return $response->product->product_name;
                    })
                    ->addColumn('subproduct_name', function ($response) {
                        return $response->subproduct->subproduct_name;
                    })
                    ->addColumn('details', function ($response) {
                        return $this->generateActionButtons('history', $response->id, ['view' => true, 'edit' => false, 'delete' => false]);
                    })
                    ->rawColumns(['details'])
                    ->make(true);
    }

}
