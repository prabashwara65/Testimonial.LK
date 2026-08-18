<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Admin;
use Spatie\Permission\Models\Role;

use Modules\Admin\Http\Repositories\UserRepository;

use App\Http\Constants\Actions;

class UserService extends MainService
{
    protected $userRepository;

    public function __construct(
        UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        $user = Admin::select();
        
        if (!empty($search)) {
            $user->where(function($query) use ($columns, $search){
                $query = $this->generateWhereLikeQuery($query, $columns, $search);

                $query->orWhere(DB::raw("CONCAT(name, ' ', last_name)"), 'like', '%' . $search . '%');
                $query->orWhere(DB::raw("CONCAT(address, ' ', address_line1, ' ', address_line2)"), 'like', '%' . $search . '%');

                $query->orWhereHas('region', function ($q) use ($search){
                    $q->where('region', 'LIKE', '%'.$search.'%');
                });
                
                $query->orWhereHas('country', function ($q) use ($search){
                    $q->where('country', 'LIKE', '%'.$search.'%');
                });
            });
        }
        if (!empty($orderBy)) {
            $user->orderBy($orderBy, $orderDirection);
        } else {
            $user->orderBy('admins.created_at', 'desc');
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
            $toggle .= '<input type="checkbox" class="custom-control-input" id="status'.$user->id.'" onchange="changeUserStatus(event.target, '. $user->id .', \''. route('admin.users.status') .'\')" ';
            $toggle .= ($user->status == 1) ? "checked" : "";
            $toggle .= '> <label class="custom-control-label" for="status'.$user->id.'"> </label> </div>';

            $temp = [
                // * customize start
                $user->id,
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
                $user->roles->pluck('name')->implode(", "),
                $toggle,
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.users', $user->id, ['view' => false, 'edit' => Actions::EDIT_USERS, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Admin::count(); // count of all the records in the database table

        return $out;
    }

    public function createUser($request)
    {
        try {
            $input = $request->only(['name', 'last_name', 'emp_id', 'username', 'nic', 'mobile', 'region_id', 'country_id', 'email', 'address', 'address_line1', 'address_line2', 'department', 'designation']);
            $input['password'] = Hash::make($request->input('password'));
            $user = $this->userRepository->create($input);

            $roles = $request['role'];
            //Checking if a role was selected
            if (!empty($roles)) {
                foreach ($roles as $role) {
                    $role_r = Role::where('id', '=', $role)->firstOrFail();
                    $user->assignRole($role_r); //Assigning role to user
                }
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

            $input = $request->only(['name', 'last_name', 'emp_id', 'username', 'nic', 'mobile', 'region_id', 'country_id', 'email', 'address', 'address_line1', 'address_line2', 'department', 'designation']);
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
        $temp['name'] = $user->name;
        $temp['last_name'] = $user->last_name;
        $temp['emp_id'] = $user->emp_id;
        $temp['username'] = $user->username;
        $temp['nic'] = $user->nic;
        $temp['mobile'] = $user->mobile;
        $temp['region_id'] = $user->region_id;
        $temp['country_id'] = $user->country_id;
        $temp['email'] = $user->email;
        $temp['address'] = $user->address;
        $temp['address_line1'] = $user->address_line1;
        $temp['address_line2'] = $user->address_line2;
        $temp['department'] = $user->department;
        $temp['designation'] = $user->designation;
        $temp['created_at'] = is_null($user->created_at)? $user->created_at: $user->created_at->format('F d, Y h:ia');
        $temp['roles'] = $user->roles()->pluck('id')->toArray();
        $temp['role_names'] = $user->roles()->pluck('name')->toArray();
        $temp['vendor_company_id'] = $user->vendor_company_id;
        
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
}