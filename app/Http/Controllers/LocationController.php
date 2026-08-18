<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use App\Models\Region;
use App\Models\Country;
use App\Models\Province;

class LocationController
{
    public function loadCountries(Request $request) {
        try {
            $region_ids = $request->selected_id;
            $temp = [];

            if(isset($region_ids)) {
                if (!is_array($region_ids)) {
                    $region_ids = [$region_ids];
                }

                foreach ($region_ids as $id) {
                    $region = Region::findOrFail($id);
                    $countries = $region->countries;

                    foreach ($countries as $country) {
                        $obj = new \stdClass();
                        $obj->id = $country->id;
                        $obj->name = $country->country;
                        array_push($temp, $obj);
                    }
                }
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

    public function loadProvinces(Request $request) {
        try {
            $country_ids = $request->selected_id;
            $temp = [];

            if(isset($country_ids)) {
                if (!is_array($country_ids)) {
                    $country_ids = [$country_ids];
                }

                foreach ($country_ids as $id) {
                    $country = Country::findOrFail($id);
                    $provinces = $country->provinces;

                    foreach ($provinces as $province) {
                        $obj = new \stdClass();
                        $obj->id = $province->id;
                        $obj->name = $province->province;
                        array_push($temp, $obj);
                    }
                }
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

    public function loadDistricts(Request $request) {
        try {
            $province_ids = $request->selected_id;
            $temp = [];

            if(isset($province_ids)) {
                if (!is_array($province_ids)) {
                    $province_ids = [$province_ids];
                }

                foreach ($province_ids as $id) {
                    $province = Province::findOrFail($id);
                    $districts = $province->districts;

                    foreach ($districts as $district) {
                        $obj = new \stdClass();
                        $obj->id = $district->id;
                        $obj->name = $district->district;
                        array_push($temp, $obj);
                    }
                }
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
}
