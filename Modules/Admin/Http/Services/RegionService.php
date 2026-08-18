<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Region;

use Modules\Admin\Http\Repositories\RegionRepository;

use App\Http\Constants\Actions;

class RegionService extends MainService
{
    protected $regionRepository;

    public function __construct(
        RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $region = Region::select();
        // * customize end

        if (!empty($search)) {
            $region = $this->generateWhereLikeQuery($region, $columns, $search);
        }

        // get the filtered row count before limiting the results
        $rows = $region->get();
        $count = count($rows);

        // limit the results for pagination
        $region->offset($offset)->limit($limit);

        $region->orderBy('regions.region', 'asc');
        $rows = $region->get();

        $data = [];

        foreach ($rows as $region) {
            $temp = [
                // * customize start
                $region->region,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.regions', $region->id, ['view' => false, 'edit' => Actions::EDIT_REGIONS, 'delete' => Actions::DELETE_REGIONS])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Region::count(); // count of all the records in the database table

        return $out;
    }

    public function createRegion($request)
    {
        try {
            $input = $request->only(['region']);
            $region = $this->regionRepository->create($input);

            return ['status' => 'success', 'region' => $region];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateRegion($request, $id)
    {
        try {
            $region = $this->regionRepository->show($id); //Get region specified by id

            $input = $request->only(['region']);
            $region->fill($input)->save();

            return ['status' => 'success', 'region' => $region];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getRegion($editId)
    {
        $region = $this->regionRepository->show($editId);

        $temp['id'] = $region->id;
        $temp['region'] = $region->region;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $this->regionRepository->delete($id);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}