<?php

namespace Modules\Vendor\Http\Controllers;

use App\Models\ActionLog;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function checkPermissionRedirect($action, $guard)
    {
        try {
            if (!Auth::user()->hasPermissionTo($action, $guard)) {
                $redirect = '';
                if(! Request::ajax()){
                    Redirect::route('vendor.permission-denied')->send();
                }

                $outPutArray = array('status' => 'error', 'message' => 'You do not have permission to perform this action: "'.$action.'"', 'data' => route('vendor.permission-denied'), 'redirect' => $redirect, 'notifyType' => 'message');
                response()->json($outPutArray)->send();
                exit();
            }
        } catch (PermissionDoesNotExist $e) {
            $redirect = '';
            if(! Request::ajax()){
                Redirect::route('vendor.permission-denied')->send();
            }

            $outPutArray = array('status' => 'error', 'message' => 'You do not have permission to perform this action: "'.$action.'"', 'data' => route('vendor.permission-denied'), 'redirect' => $redirect, 'notifyType' => 'message');
            response()->json($outPutArray)->send();
            exit();
        }
    }

    protected function checkHasPermission($action, $guard)
    {
        try {
            if (!Auth::user()->hasPermissionTo($action, $guard)) {
                return false;
            }
            return true;
        } catch (PermissionDoesNotExist $e) {
            return false;
        }
    }

    /**
     * Insert User Actions to Log Table
     *
     * For every action defined in permissions, an action log records should be
     * created except for GET (data retrieval) functions. But any action that modifies system data should
     * be logged along with the permission.
     *
     * @param $action String Name of the permission
     * @param $subject String ID of the item being modified or created
     * @param $parameters String All of input parameters
     * @param $response String Response data or any results of the action
     * @param $comments String Any other data that should be saved
     *
     * @access public
     * @author Prasith Fernando
     */
    protected function logAction($action, $subject, $parameters, $response)
    {
        $log = new ActionLog();

        $log->user = Auth::user()->name . " " . Auth::user()->last_name;
        $log->vendor_company_id = Auth::user()->vendor_company_id;
        $log->ip = Request::ip();
        $log->action = $action;
        $log->subject = $subject;
        $log->parameters = $parameters;
        $log->response = $response;

        $log->save();
    }

    protected function getOrderByColumn($columns, $orderColumn)
    {
        $keys = array_keys($columns);
        return $columns[$keys[($orderColumn)]];
    }
}
