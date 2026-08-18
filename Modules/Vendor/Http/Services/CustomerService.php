<?php
namespace Modules\Vendor\Http\Services;

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
        $customer = User::whereHas('responses', function ($q) {
            $q->where('responses.vendor_company_id', auth()->user()->vendor_company_id);
        });

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
                $this->generateActionButtons('vendor.customers', $customer->id, ['view' => Actions::VIEW_CUSTOMERS, 'edit' => false, 'delete' => false]),
                $this->generateCustomerButtons('vendor.customers', $customer->id, ['testimonial' => $customer->testimonial_count, 'feedback' => false, 'reward' => false, 'assignreward' => false]),
                $this->generateCustomerButtons('vendor.customers', $customer->id, ['testimonial' => false, 'feedback' => $customer->feedback_count, 'reward' => false, 'assignreward' => false]),
                $this->generateCustomerButtons('vendor.customers', $customer->id, ['testimonial' => false, 'feedback' => false, 'reward' => $customer->reward_count, 'assignreward' => false]),
                $this->generateCustomerButtons('vendor.customers', $customer->id, ['testimonial' => false, 'feedback' => false, 'reward' => false, 'assignreward' => true])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = User::count(); // count of all the records in the database table

        return $out;
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

    public function getResponse($id, $type)
    {
        try {
            $data['responses'] = Response::where('vendor_company_id', auth()->user()->vendor_company_id)
                                ->where('customer_id', $id)
                                ->where('type', $type)
                                ->orderBy('created_at', 'desc')
                                ->paginate(1);

            return $data;
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function getReward($id)
    {
        try {
            $users = User::find($id);
            $data['rewards'] = $users->rewards()->orderBy('id', 'desc')->paginate(1);

            return $data;
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function assignReward($id)
    {
        try {

            $rewards['id'] = $id;
            $rewards['rewards'] = Reward::where('vendor_company_id', auth()->user()->vendor_company_id)
                                            ->where('date', '>', date('Y-m-d'))
                                            ->get();

            return $rewards;
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function storeReward($request, $id)
    {
        try {
            $customer = $this->customerRepository->show($id);; //Get product specified by id

            if($request->reward_type == 'discount') {
                $reward = Reward::find($request->discount);
            }
            else {
                $reward = Reward::find($request->gift);
            }

            $customer->rewards()->attach($reward, ['vendor_company_id' => auth()->user()->vendor_company_id, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);

            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Customers';

            $columnHeaders = array('Customer Name', 'NIC', 'Email', 'Mobile', 'Testimonial Count', 'Feedback Count', 'Rewards Count');
            $tableColumns = array('customer_name', 'nic', 'email', 'mobile', 'testimonial_count', 'feedback_count', 'reward_count');

            $model = User::whereHas('rewards', function ($q) {
                        $q->where('rewards.vendor_company_id', auth()->user()->vendor_company_id);
                    });

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = array('id', 'nic', 'email', 'mobile');
            array_push( $select,
                        DB::raw("CONCAT(users.name, ' ', users.last_name) AS 'customer_name'")
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

            $query->orWhere(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $search . '%');
        });

        return $model;
    }

}
