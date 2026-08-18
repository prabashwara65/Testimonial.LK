<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\ApiController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Api\AuthRequest;

use App\Models\Vendor;


class AuthController extends ApiController
{
    public function login(AuthRequest $request)
    {
        // only check the app version if the version is passed in the request to avoid breaking login in older versions without version check
        if ($request->has('app_version')) {
            $versionValidation = $this->validateAppVersion($request->input('app_version'));
            if (!$versionValidation['status']) {
                return $this->sendResponse('error', 'New version available', ['version'=> $versionValidation['version'], 'url' => $versionValidation['url']], 400);
            }
        }

        $user = Vendor::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendResponse('error', 'These credentials do not match our records', [], 404);
        } elseif ($user->status == 0) {
            return $this->sendResponse('error', 'Account disabled', [], 401);
        }
    
        $token = $user->createToken('auth-token')->plainTextToken;

        $data = [
            "id" => $user->id,
            "emp_id" => $user->emp_id,
            "name" => $user->name,
            "last_name" => $user->last_name,
            "nic" => $user->nic,
            "email" => $user->email,
            "mobile" => $user->mobile,
            "address" => $user->address,
            "address_line1" => $user->address_line1,
            "address_line2" => $user->address_line2,
            "designation" => $user->designation,
            "department" => $user->department,
            "vendor_company_id" => $user->vendor_company_id,
            "vendor_company" => $user->vendorCompany->company_name,
            "region_id" => $user->region_id,
            "region" => $user->region->region,
            "country_id" => $user->country_id,
            "country" => $user->country->country
        ];
    
        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Logged in successfully',
            'data' => $data,
            'token' => $token
        ];
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->sendResponse('success', 'Successfully logged out', [], 200);
    }

    public function validateAppVersion($currentVersion)
    {
        $version = DB::table('app_versions')->orderBy('id', 'desc')->first();
        if($version->version > $currentVersion) {
            return [
                'status' => false,
                'version' => $version->version,
                'url' => $version->url
            ];
        } else {
            return ['status' => true];
        }
    }
}