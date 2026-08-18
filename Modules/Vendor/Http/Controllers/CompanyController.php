<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Modules\Vendor\Http\Requests\CompanyRequest;

use App\Models\VendorCompany;
use App\Models\Region;

use Modules\Vendor\Http\Services\CompanyService;

use App\Http\Constants\Actions;


class CompanyController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Company Details";

    function __construct(CompanyService $companyService)
    { 
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_COMPANY, 'vendor');

        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_COMPANY, 'vendor');
        $data['editRoute'] = 'vendor.company.edit';

        $data['title'] = $this->pageTitle;

        $data['company'] = VendorCompany::find(auth()->user()->vendor_company_id);

        return view('vendor::company.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_COMPANY, 'vendor');

        $data['company'] = $this->companyService->getCompany($id); //Get company with specified id
        $data['regions'] = Region::get();
        $data['loadCountriesUrl'] = route('load-countries');

        $view = View::make('vendor::company.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     * @param CompanyRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(CompanyRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_COMPANY, 'vendor');

            $result = $this->companyService->updateCompany($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_COMPANY, $result['company']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Company updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
