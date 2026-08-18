<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Country;

use Modules\Admin\Http\Repositories\CountryRepository;

use App\Http\Constants\Actions;

class CountryService extends MainService
{
    protected $countryRepository;

    public function __construct(
        CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $country = Country::select();

        if (!empty($search)) {
            $country->where(function($query) use ($columns, $search){
                $query = $this->generateWhereLikeQuery($query, $columns, $search);

                $query->orWhereHas('region', function ($q) use ($search){
                    $q->where('region', 'LIKE', '%'.$search.'%');
                });
            });
        }

        // get the filtered row count before limiting the results
        $rows = $country->get();
        $count = count($rows);

        // limit the results for pagination
        $country->offset($offset)->limit($limit);

        $country->orderBy('countries.region_id', 'asc')->orderBy('countries.country', 'asc');
        $rows = $country->get();

        $data = [];

        foreach ($rows as $country) {
            $temp = [
                // * customize start
                $country->id,
                $country->country,
                $country->region->region,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.countries', $country->id, ['view' => false, 'edit' => Actions::EDIT_COUNTRIES, 'delete' => Actions::DELETE_COUNTRIES])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Country::count(); // count of all the records in the database table

        return $out;
    }

    public function createCountry($request)
    {
        try {
            $input = $request->only(['region_id', 'country']);
            $country = $this->countryRepository->create($input);

            return ['status' => 'success', 'country' => $country];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateCountry($request, $id)
    {
        try {
            $country = $this->countryRepository->show($id); //Get country specified by id

            $input = $request->only(['region_id', 'country']);
            $country->fill($input)->save();

            return ['status' => 'success', 'country' => $country];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getCountry($editId)
    {
        $country = $this->countryRepository->show($editId);

        $temp['id'] = $country->id;
        $temp['region_id'] = $country->region_id;
        $temp['country'] = $country->country;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $country = $this->countryRepository->delete($id);
            return ['status' => 'success', 'country' => $country];
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
