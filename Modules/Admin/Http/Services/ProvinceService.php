<?php

namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Province;

use Modules\Admin\Http\Repositories\ProvinceRepository;

use App\Http\Constants\Actions;

class ProvinceService extends MainService
{
    protected $provinceRepository;

    public function __construct(
        ProvinceRepository $provinceRepository
    ) {
        $this->provinceRepository = $provinceRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $province = Province::select();

        if (!empty($search)) {
            $province->where(function ($query) use ($columns, $search) {
                $query = $this->generateWhereLikeQuery($query, $columns, $search);

                $query->orWhereHas('country', function ($q) use ($search) {
                    $q->where('country', 'LIKE', '%' . $search . '%');
                });

                $query->orWhereHas('region', function ($q) use ($search) {
                    $q->where('region', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // get the filtered row count before limiting the results
        $rows = $province->get();
        $count = count($rows);

        // limit the results for pagination
        $province->offset($offset)->limit($limit);

        $province->orderBy('provinces.country_id', 'asc')->orderBy('provinces.province', 'asc');
        $rows = $province->get();

        $data = [];

        foreach ($rows as $province) {
            $temp = [
                // * customize start
                $province->id,
                $province->province,
                $province->country->country,
                $province->region->region,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.provinces', $province->id, ['view' => false, 'edit' => Actions::EDIT_PROVINCES, 'delete' => Actions::DELETE_PROVINCES])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Province::count(); // count of all the records in the database table

        return $out;
    }

    public function createProvince($request)
    {
        try {
            $input = $request->only(['region_id', 'country_id', 'province']);
            $province = $this->provinceRepository->create($input);

            return ['status' => 'success', 'province' => $province];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateProvince($request, $id)
    {
        try {
            $province = $this->provinceRepository->show($id); //Get province specified by id

            $input = $request->only(['region_id', 'country_id', 'province']);
            $province->fill($input)->save();

            return ['status' => 'success', 'province' => $province];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getProvince($editId)
    {
        $province = $this->provinceRepository->show($editId);

        $temp['id'] = $province->id;
        $temp['region_id'] = $province->region_id;
        $temp['country_id'] = $province->country_id;
        $temp['province'] = $province->province;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $province = $this->provinceRepository->delete($id);
            return ['status' => 'success', 'province' => $province];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
