<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

use App\Models\VendorCompany;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Response;
use App\Models\Campaign;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function stepOne()
    {
        $data['vendorCompanies'] = VendorCompany::where('status', 1)->get();
        $data['loadProductsUrl'] = route('customer-response.load-products');
        $data['loadSubproductsUrl'] = route('customer-response.load-subproducts');

        return view('customerResponse.step-one', $data);
    }

    public function postStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'vendor_company_id' => 'required',
            'product_id' => 'required',
            'subproduct_id' => 'required',
        ]);

        $validatedData['customer_id'] = auth()->user()->id;
        $validatedData['input_source'] = 'Web';

        if (empty($request->session()->get('response'))) {
            $response = new Response();
            $response->fill($validatedData);
            $request->session()->put('response', $response);
        } else {
            $response = $request->session()->get('response');
            $response->fill($validatedData);
            $request->session()->put('response', $response);
        }

        return redirect()->route('customer-response.step-two');
    }

    public function stepTwo(Request $request)
    {
        if (empty($request->session()->get('response'))) {
            return redirect()->route('customer-response.step-one');
        }

        return view('customerResponse.step-two');
    }

    public function postStepTwo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'video' => 'mimes:mp4,webm,mov|max:15360|required_without_all:audio,image,text,textarea',
            'audio' => 'mimes:mp3,webm,wav|max:15360|required_without_all:video,image,text,textarea',
            'image' => 'mimes:jpg,jpeg,png,gif|max:15360|required_without_all:video,audio,text,textarea',
            'text' => 'mimes:csv,txt,xlx,xls,pdf|max:15360|required_without_all:video,audio,image,textarea',
            'textarea' => 'required_without_all:video,audio,image,text'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $input = $request->only('type');
        $input['response_type'] = 'Record';
        $response = $request->session()->get('response');
        $response->fill($input)->save();

        $paths = [];
        if ($request->file('video')) $paths['video'] = $request->file('video')->store('records/videos', 'public');
        if ($request->file('audio')) $paths['audio'] = $request->file('audio')->store('records/audios', 'public');
        if ($request->file('image')) $paths['image'] = $request->file('image')->store('records/images', 'public');
        if ($request->file('text')) $paths['text'] = $request->file('text')->store('records/texts', 'public');
        if ($request->textarea) {
            $filename = 'records/texts/' . uniqid('text_', true) . '.txt';
            Storage::disk('local')->put('public/' . $filename, $request->textarea);

            $paths['text'] = $filename;
        }

        $response->responseRecord()->create($paths);

        $request->session()->forget('response');

        return response()->json("Uploaded Successfully");
    }

    public function loadProducts(Request $request)
    {
        try {
            $vendor_company_id = $request->selected_id;

            $vendorCompany = VendorCompany::findOrFail($vendor_company_id);

            $products = $vendorCompany->products;

            $temp = [];
            foreach ($products as $product) {
                $obj = new \stdClass();
                $obj->id = $product->id;
                $obj->name = $product->product_name;
                array_push($temp, $obj);
            }

            $data['options'] = $temp;

            $view = View::make('common.common-dropdown-options', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function loadSubproducts(Request $request)
    {
        try {
            $product_id = $request->selected_id;

            $products = Product::findOrFail($product_id);
            $subproducts = $products->subproducts;

            $temp = [];
            foreach ($subproducts as $subproduct) {
                $obj = new \stdClass();
                $obj->id = $subproduct->id;
                $obj->name = $subproduct->subproduct_name;
                array_push($temp, $obj);
            }

            $data['options'] = $temp;

            $view = View::make('common.common-dropdown-options', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function videoRecord()
    {
        try {
            $view = View::make('recorders.videoRecorder')->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function audioRecord()
    {
        try {
            $view = View::make('recorders.audioRecorder')->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function imageRecord()
    {
        try {
            $view = View::make('recorders.imageRecorder')->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
