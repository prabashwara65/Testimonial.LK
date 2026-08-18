<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Branch;

use Modules\Vendor\Http\Repositories\BranchRepository;

use App\Http\Constants\Actions;

class BranchService extends MainService
{
    protected $branchRepository;

    public function __construct(
        BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $branch = Branch::where('vendor_company_id', auth()->user()->vendor_company_id);

        if (!empty($search)) {
            $branch = $this->search($branch, $columns, $search);
        }
        if (!empty($orderBy)) {
            $branch->orderBy($orderBy, $orderDirection);
        } else {
            $branch->orderBy('branches.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $branch->get();
        $count = count($rows);

        // limit the results for pagination
        $branch->offset($offset)->limit($limit);
        $rows = $branch->get();

        $data = [];

        foreach ($rows as $branch) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$branch->id.'" onchange="changeUserStatus(event.target, '. $branch->id .', \''. route('vendor.branches.status') .'\')" ';
            $toggle .= ($branch->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$branch->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $branch->branch_code,
                $branch->name,
                $branch->contact_no,
                $branch->email,
                $branch->address . " " . $branch->address_line1 . " " . $branch->address_line2,
                $branch->district->district,
                $branch->province->province,
                $branch->country->country,
                $branch->region->region,
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.branches', $branch->id, ['view' => false, 'edit' => Actions::EDIT_BRANCHES, 'delete' => Actions::DELETE_BRANCHES])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Branch::count(); // count of all the records in the database table

        return $out;
    }

    public function createBranch($request)
    {
        try {
            $input = $request->only(['branch_code', 'name', 'contact_no', 'email', 'address', 'address_line1', 'address_line2', 'region_id', 'country_id', 'province_id', 'district_id']);
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;
            $branch = $this->branchRepository->create($input);

            return ['status' => 'success', 'branch' => $branch];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateBranch($request, $id)
    {
        try {
            $branch = $this->branchRepository->show($id); //Get branch specified by id

            $input = $request->only(['branch_code', 'name', 'contact_no', 'email', 'address', 'address_line1', 'address_line2', 'region_id', 'country_id', 'province_id', 'district_id']);
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;
            $branch->fill($input)->save();

            return ['status' => 'success', 'branch' => $branch];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getBranch($editId)
    {
        $branch = $this->branchRepository->show($editId);

        $temp['id'] = $branch->id;
        $temp['branch_code'] = $branch->branch_code;
        $temp['name'] = $branch->name;
        $temp['contact_no'] = $branch->contact_no;
        $temp['email'] = $branch->email;
        $temp['address'] = $branch->address;
        $temp['address_line1'] = $branch->address_line1;
        $temp['address_line2'] = $branch->address_line2;
        $temp['region_id'] = $branch->region_id;
        $temp['country_id'] = $branch->country_id;
        $temp['province_id'] = $branch->province_id;
        $temp['district_id'] = $branch->district_id;

        return $temp;
    }

    public function delete($id)
    {
        try {
            $this->branchRepository->delete($id);
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStatus($request)
    {
        try {
            $branch = $this->branchRepository->show($request->id); //Get branch specified by id

            $input = $request->only(['status']);
            $branch->fill($input)->save();

            foreach ($branch->vendors as $vendor) {
                $vendor->branch_status = $request->status;
                $vendor->save();
            }

            return ['status' => 'success', 'branch' => $branch];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Branches';

            $columnHeaders = array('Branch ID', 'Branch Name', 'Contact No', 'Email', 'Address', 'District', 'Province', 'Country', 'Region', 'Status');
            $tableColumns = array('branch_code', 'name', 'contact_no', 'email', 'address', 'address_line1', 'address_line2', 'country', 'region', 'status');

            $model = Branch::where('vendor_company_id', auth()->user()->vendor_company_id)
                            ->join('countries', 'branches.country_id', 'countries.id')
                            ->join('regions', 'branches.region_id', 'regions.id');

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = $tableColumns;
            array_push( $select,
                        DB::raw("(CASE WHEN branches.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
                    );
            $model = $model->get($select);

            return $this->exportReport($type, $searchValue, $title, $columnHeaders, $tableColumns, $model);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function search($model, $columns, $search)
    {
        $model->where(function($query) use ($columns, $search){
            $query = $this->generateWhereLikeQuery($query, $columns, $search);

            $query->orWhereHas('region', function ($q) use ($search){
                $q->where('regions.region', 'LIKE', '%'.$search.'%');
            });

            $query->orWhereHas('country', function ($q) use ($search){
                $q->where('countries.country', 'LIKE', '%'.$search.'%');
            });
        });

        return $model;
    }

}
