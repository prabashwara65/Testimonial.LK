<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\User;
use App\Models\Response;
use App\Models\Reward;

use Modules\Vendor\Http\Repositories\CustomerRepository;

use App\Http\Constants\Actions;

class CustomerService extends MainService
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $customer = User::select();

        if (!empty($search)) {
            $customer = $this->search($customer, $columns, $search);
        }
        if (!empty($orderBy)) {
            $customer->orderBy($orderBy, $orderDirection);
        } else {
            $customer->orderBy('users.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $customer->get();
        $count = count($rows);

        // limit the results for pagination
        $customer->offset($offset)->limit($limit);
        $rows = $customer->get();

        $data = [];

        foreach ($rows as $customer) {
            $temp = [
                // * customize start
                $customer->name . " " . $customer->last_name,
                $customer->nic,
                $customer->email,
                $customer->mobile,
                $customer->address . " " . $customer->address_line1 . " " . $customer->address_line2,
                $customer->country->country,
                $customer->region->region,
                $this->generateActionButtons('admin.customers', $customer->id, ['view' => false, 'edit' => Actions::EDIT_COUNTRIES, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = User::count(); // count of all the records in the database table

        return $out;
    }

    public function updateCustomer($request, $id)
    {
        try {
            $customer = $this->customerRepository->show($id); //Get vendor specified by id

            $input = $request->only(['name', 'last_name', 'nic', 'mobile', 'email', 'address', 'address_line1', 'address_line2', 'username', 'region_id', 'country_id']);
            if (!empty($request->input('update_password'))) {
                $input['password'] = Hash::make($request->input('password'));
            }
            $customer->fill($input)->save();

            return ['status' => 'success', 'customer' => $customer];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getCustomer($id)
    {
        $customer = $this->customerRepository->show($id);

        $temp['id'] = $customer->id;
        $temp['name'] = $customer->name;
        $temp['last_name'] = $customer->last_name;
        $temp['nic'] = $customer->nic;
        $temp['email'] = $customer->email;
        $temp['mobile'] = $customer->mobile;
        $temp['address'] = $customer->address;
        $temp['address_line1'] = $customer->address_line1;
        $temp['address_line2'] = $customer->address_line2;
        $temp['region_id'] = $customer->region_id;
        $temp['country_id'] = $customer->country_id;

        return $temp;
    }

    private function search($model, $columns, $search)
    {
        $model->where(function($query) use ($columns, $search){
            $query = $this->generateWhereLikeQuery($query, $columns, $search);

            $query->orWhere(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $search . '%');
        });

        return $model;
    }

}
