<?php
namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Vendor;
use Spatie\Permission\Models\Role;

use Modules\Vendor\Http\Repositories\UserRepository;

use App\Http\Constants\Actions;

class UserService extends MainService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $user = Vendor::whereHas('roles', function ($q) {
            $q->where('name', '!=', 'Vendor'); //remove main vendor accounts
        })->where('vendor_company_id', '=', auth()->user()->vendor_company_id);

        if (!empty($search)) {
            $user = $this->search($user, $columns, $search);
        }
        if (!empty($orderBy)) {
            $user->orderBy($orderBy, $orderDirection);
        } else {
            $user->orderBy('vendors.created_at', 'desc');
        }

        // get the filtered row count before limiting the results
        $rows = $user->get();
        $count = count($rows);

        // limit the results for pagination
        $user->offset($offset)->limit($limit);
        $rows = $user->get();

        $data = [];

        foreach ($rows as $user) {

            $toggle = '<div class="custom-control custom-switch custom-control-success mb-2">';
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$user->id.'" onchange="changeUserStatus(event.target, '. $user->id .', \''. route('vendor.users.status') .'\')" ';
            $toggle .= ($user->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$user->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $user->emp_id,
                $user->name . " " . $user->last_name,
                $user->nic,
                $user->email,
                $user->mobile,
                $user->address . " " . $user->address_line1 . " " . $user->address_line2,
                $user->country->country,
                $user->region->region,
                $user->designation,
                $user->department,
                (isset($user->branch->name)) ? $user->branch->name : 'No Branch Assigned',
                $user->roles->pluck('name')->implode(", "),
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.users', $user->id, ['view' => Actions::VIEW_USERS, 'edit' => Actions::EDIT_USERS, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Vendor::count(); // count of all the records in the database table

        return $out;
    }

    public function createUser($request)
    {
        try {
            $input = $request->only(['emp_id', 'name', 'last_name', 'nic', 'email', 'mobile', 'address', 'address_line1', 'address_line2', 'username', 'region_id', 'country_id', 'designation', 'department', 'branch_id', 'incentive_cal', 'incentive_rate', 'bank_account', 'bank', 'bank_branch']);
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;
            $input['password'] = Hash::make($request->input('password'));
            $user = $this->userRepository->create($input);

            $roles = $request['role'];
            //Checking if a role was selected
            if (!empty($roles)) {
                $user->roles()->sync($roles); //Assigning role to user
            }

            return ['status' => 'success', 'user' => $user];
        } catch (Exception $ex) {
            Log::error($ex);
            return $ex;
        }
    }

    public function updateUser($request, $id)
    {
        try {
            $user = $this->userRepository->show($id); //Get user specified by id

            $input = $request->only(['emp_id', 'name', 'last_name', 'nic', 'email', 'mobile', 'address', 'address_line1', 'address_line2', 'username', 'region_id', 'country_id', 'designation', 'department', 'branch_id', 'incentive_cal', 'incentive_rate', 'bank_account', 'bank', 'bank_branch']);
            $input['vendor_company_id'] = auth()->user()->vendor_company_id;
            if (!empty($request->input('update_password'))) {
                $input['password'] = Hash::make($request->input('password'));
            }
            $user->fill($input)->save();

            $role = $request['role']; //Retreive all roles
            if (isset($role)) {
                $user->roles()->sync($role);  //If one or more role is selected associate user to roles
            } else {
                $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
            }

            return ['status' => 'success', 'user' => $user];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getUser($editId)
    {
        $user = $this->userRepository->show($editId);

        $temp['id'] = $user->id;
        $temp['emp_id'] = $user->emp_id;
        $temp['name'] = $user->name;
        $temp['last_name'] = $user->last_name;
        $temp['nic'] = $user->nic;
        $temp['email'] = $user->email;
        $temp['mobile'] = $user->mobile;
        $temp['address'] = $user->address;
        $temp['address_line1'] = $user->address_line1;
        $temp['address_line2'] = $user->address_line2;
        $temp['region_id'] = $user->region_id;
        $temp['country_id'] = $user->country_id;
        $temp['username'] = $user->username;
        $temp['department'] = $user->department;
        $temp['designation'] = $user->designation;
        $temp['branch_id'] = $user->branch_id;
        $temp['roles'] = $user->roles()->pluck('id')->toArray();
        $temp['incentive_cal'] = $user->incentive_cal;
        $temp['incentive_rate'] = $user->incentive_rate;
        $temp['bank_account'] = $user->bank_account;
        $temp['bank'] = $user->bank;
        $temp['bank_branch'] = $user->bank_branch;
        
        return $temp;
    }

    public function updateStatus($request)
    {
        try {
            $user = $this->userRepository->show($request->id); //Get user specified by id

            $input = $request->only(['status']);
            $user->fill($input)->save();

            return ['status' => 'success', 'user' => $user];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function export($type, $columns, $searchValue)
    {
        try {

            $title = 'Users';

            $columnHeaders = array('EMP ID', 'Name', 'NIC', 'Email', 'Mobile', 'Address', 'Country', 'Region', 'Designation', 'Department', 'Branch', 'Status');
            $tableColumns = array('emp_id', 'name', 'nic', 'email', 'mobile', 'address', 'country', 'region', 'designation', 'department', 'branch', 'status');

            $model = Vendor::whereHas('roles', function ($q) {
                                $q->where('roles.name', '!=', 'Vendor'); //remove main vendor accounts
                            })->where('vendors.vendor_company_id', '=', auth()->user()->vendor_company_id)
                            ->join('countries', 'vendors.country_id', 'countries.id')
                            ->join('regions', 'vendors.region_id', 'regions.id')
                            ->join('branches', 'vendors.branch_id', 'branches.id');

            if (!empty($searchValue)) {
                $model = $this->search($model, $columns, $searchValue);
            }

            $select = array('vendors.emp_id', 'vendors.nic', 'vendors.email', 'vendors.mobile', 'countries.country', 'regions.region', 'vendors.designation', 'vendors.department');
            array_push( $select,
                        DB::raw("CONCAT(vendors.name, ' ', vendors.last_name) AS 'name'"),
                        DB::raw("CONCAT(vendors.address, ' ', vendors.address_line1, ' ', vendors.address_line2) AS 'address'"),
                        DB::raw("branches.name AS branch"),
                        DB::raw("(CASE WHEN vendors.status = '1' THEN 'Active' ELSE 'Inactive' END) AS status")
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

            $query->orWhere(DB::raw("CONCAT(vendors.name, ' ', vendors.last_name)"), 'like', '%' . $search . '%');
            $query->orWhere(DB::raw("CONCAT(vendors.address, ' ', vendors.address_line1, ' ', vendors.address_line2)"), 'like', '%' . $search . '%');

            $query->orWhereHas('region', function ($q) use ($search){
                $q->where('regions.region', 'LIKE', '%'.$search.'%');
            });
            
            $query->orWhereHas('country', function ($q) use ($search){
                $q->where('countries.country', 'LIKE', '%'.$search.'%');
            });

            $query->orWhereHas('branch', function ($q) use ($search){
                $q->where('branches.name', 'LIKE', '%'.$search.'%');
            });
        });

        return $model;
    }
}