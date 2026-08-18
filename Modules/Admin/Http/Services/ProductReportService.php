<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Subproduct;

use App\Http\Constants\Actions;

class ProductReportService extends MainService
{
    private $roleRepository;

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm)
    {
        $subproduct = Subproduct::select();

        if (!empty($search)) {
            $subproduct->where(function($query) use ($columns, $search){
                $query = $this->generateWhereLikeQuery($query, $columns, $search);
            });
        }
        if (!empty($orderBy)) {
            $subproduct->orderBy($orderBy, $orderDirection);
        } else {
            $subproduct->orderBy('created_at', 'desc');
        }

        if ($filterForm['vendor_company_id'] != 'Any') {
            $subproduct->whereHas('product', function ($q) use ($filterForm) {
                $q->where('vendor_company_id', $filterForm['vendor_company_id']);
            });
        }

        /// get the filtered row count before limiting the results
        $rows = $subproduct->get();
        $count = count($rows);

        // limit the results for pagination
        $subproduct->offset($offset)->limit($limit);
        $rows = $subproduct->get();

        $data = [];
        foreach ($rows as $subproduct) {

            $temp = [
                $subproduct->product->company->company_name,
                $subproduct->product->company->br_no,
                $subproduct->product->product_code,
                $subproduct->product->product_name,
                $subproduct->subproduct_code,
                $subproduct->subproduct_name,
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Subproduct::count(); // count of all the records in the database table

        return $out;
    }

    public function getRole($editId)
    {
        $role = $this->roleRepository->show($editId);

        $temp['id'] = $role->id;
        $temp['name'] = $role->name;
        $temp['permissions'] = $role->permissions()->pluck('name')->toArray();

        return $temp;
    }
}