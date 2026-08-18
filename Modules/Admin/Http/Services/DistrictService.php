<?php

namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\District;

use Modules\Admin\Http\Repositories\DistrictRepository;

use App\Http\Constants\Actions;

class DistrictService extends MainService
{
    protected $districtRepository;

    public function __construct(DistrictRepository $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $district = District::select();

        if (!empty($search)) {
            $district->where(function ($query) use ($columns, $search) {
                $query = $this->generateWhereLikeQuery($query, $columns, $search);

                $query->orWhereHas('country', function ($q) use ($search) {
                    $q->where('country', 'LIKE', '%' . $search . '%');
                });

                $query->orWhereHas('region', function ($q) use ($search) {
                    $q->where('region', 'LIKE', '%' . $search . '%');
                });

                $query->orWhereHas('province', function ($q) use ($search) {
                    $q->where('province', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // get the filtered row count before limiting the results
        $rows = $district->get();
        $count = count($rows);

        // limit the results for pagination
        $district->offset($offset)->limit($limit);

        $district->orderBy('districts.country_id', 'asc')->orderBy('districts.district', 'asc');
        $rows = $district->get();

        $data = [];

        foreach ($rows as $district) {
            $temp = [
                // * customize start
                $district->id,
                $district->district,
                $district->province->province,
                $district->country->country,
                $district->region->region,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.districts', $district->id, ['view' => false, 'edit' => Actions::EDIT_DISTRICTS, 'delete' => Actions::DELETE_DISTRICTS])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = District::count(); // count of all the records in the database table

        return $out;
    }

    public function createDistrict($request)
    {
        try {
            $input = $request->only(['region_id', 'country_id', 'province_id', 'district']);
            $district = $this->districtRepository->create($input);

            return ['status' => 'success', 'district' => $district];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateDistrict($request, $id)
    {
        try {
            $district = $this->districtRepository->show($id); //Get district specified by id

            $input = $request->only(['region_id', 'country_id', 'province_id', 'district']);
            $district->fill($input)->save();

            return ['status' => 'success', 'district' => $district];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getDistrict($editId)
    {
        $district = $this->districtRepository->show($editId);

        $temp['id'] = $district->id;
        $temp['region_id'] = $district->region_id;
        $temp['country_id'] = $district->country_id;
        $temp['province_id'] = $district->province_id;
        $temp['district'] = $district->district;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $district = $this->districtRepository->delete($id);
            return ['status' => 'success', 'district' => $district];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
