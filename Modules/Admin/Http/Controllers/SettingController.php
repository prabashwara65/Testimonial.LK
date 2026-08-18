<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Modules\Admin\Http\Requests\SettingRequest;

use App\Models\Setting;

use Modules\Admin\Http\Services\SettingService;

use App\Http\Constants\Actions;


class SettingController extends Controller
{
    /** @var string Title shown at top left of the page */
    private $pageTitle = "Settings";

    function __construct(SettingService $settingService)
    { 
        $this->settingService = $settingService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->checkPermissionRedirect(Actions::VIEW_SETTINGS, 'admin');

        $data['editPermission'] = $this->checkHasPermission(Actions::EDIT_SETTINGS, 'admin');
        $data['editRoute'] = 'admin.settings.edit';

        $data['title'] = $this->pageTitle;

        $data['settings'] = Setting::get();

        return view('admin::settings.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->checkPermissionRedirect(Actions::EDIT_SETTINGS, 'admin');

        $data['setting'] = $this->settingService->getSetting($id); //Get setting with specified id

        $view = View::make('admin::settings.edit', $data)->render();
        $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal',  'modalSize' => 'xl');
        return $outPutArray;
    }

    /**
     * Update the specified resource in storage.
     * @param SettingRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(SettingRequest $request, $id)
    {
        try {
            $this->checkPermissionRedirect(Actions::EDIT_SETTINGS, 'admin');

            $result = $this->settingService->updateSetting($request, $id);

            if ($result['status'] == 'success') {
                $this->logAction(Actions::EDIT_SETTINGS, $result['setting']->id, json_encode(request()->all()), json_encode($result));

                $outPutArray = array('status' => 'success', 'message' => 'Setting updated successfully', 'data' => '', 'redirect' => '', 'notifyType' => 'message');
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
