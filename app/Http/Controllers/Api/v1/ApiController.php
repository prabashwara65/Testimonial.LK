<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected function sendValidationFailResponse($validator)
    {
        $outPutArray = array('status' => 'error', 'message' => $validator->errors()->first(), 'code' => 422);
        return response()->json($outPutArray, 422);
    }

    protected function sendResponse($status, $message, $data, $httpCode)
    {
        $outPutArray = array('status' => $status, 'code' => $httpCode);
        if (!empty($message)) {
            $outPutArray['message'] = $message;
        }
        if (!empty($data)) {
            $outPutArray['data'] = $data;
        }
        return response()->json($outPutArray, $httpCode);
    }
}
