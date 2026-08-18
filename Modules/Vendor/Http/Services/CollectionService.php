<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use DataTables;

use App\Models\Response;

class CollectionService extends MainService
{
    public function getDataTable()
    {
        $responses = Response::where('emp_id', auth()->user()->id)->get();

        return DataTables::of($responses)
            ->editColumn('created_at', '{{ date("Y/m/d - h:i A", strtotime($created_at)) }}')
            ->addColumn('nic', function ($response) {
                return $response->user->nic;
            })
            ->addColumn('customer_name', function ($response) {
                return $response->user->name . ' ' . $response->user->last_name;
            })
            ->editColumn('type', '{{ ($type == "1") ? "Testimonial" : "Feedback" }}')
            ->addColumn('campaign', function ($response) {
                return $response->campaign->campaign_name;
            })
            ->addColumn('product_name', function ($response) {
                return $response->product->product_name;
            })
            ->addColumn('subproduct_name', function ($response) {
                return $response->subproduct->subproduct_name;
            })
            ->addColumn('rating', function ($response) {
                $rating = '';
                for ($i = 0; $i < $response->rating; $i++) { $rating .= '★'; }
                return $rating;
            })
            ->addColumn('details', function ($response) {
                return $this->generateActionButtons('collection', $response->id, ['view' => true, 'edit' => false, 'delete' => false]);
            })
            ->rawColumns(['details'])
            ->make(true);
    }
}
