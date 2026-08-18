<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Modules\Admin\Http\Repositories\RoleRepository;

use App\Http\Constants\Actions;

class RoleService extends MainService
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $model = Role::select();
        // * customize end

        if (!auth()->user()->hasRole('Super Admin')) {
            $roles = ['Super Admin', 'Admin', 'Vendor', 'Sales Rep'];
            $model->where('guard_name', '=', 'admin')->whereNotIn('name', $roles);
        }
        else {
            $model->where('vendor_company_id', '=', 0);
        }

        if (!empty($search)) {
            unset($columns['Permissions@no-sort@']);
            $model = $this->generateWhereLikeQuery($model, $columns, $search);
        }
        if (!empty($orderBy)) {
            $model->orderBy($orderBy, $orderDirection);
        }

        // get the filtered row count before limiting the results
        $rows = $model->get();
        $count = count($rows);

        // limit the results for pagination
        $model->offset($offset)->limit($limit);
        $rows = $model->get();

        $data = [];
        foreach ($rows as $row) {
            $temp = [
                // * customize start
                $row->name,
                $row->guard_name,
                $row->permissions->pluck('name')->implode(", "),
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.roles', $row->id, ['view' => false, 'edit' => Actions::EDIT_ROLES, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Role::count(); // count of all the records in the database table

        return $out;
    }

    public function createRole(Request $request)
    {
        try {
            $name = $request['name'];
            $role = new Role();
            $role->name = $name;

            $permissions = $request['permissions'];

            $role->save();
            //Looping thru selected permissions
            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }
            return ['status' => 'success', 'role' => $role];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateRole($request, $id)
    {
        try {
            $role = $this->roleRepository->show($id); //Get role with the given id
            $input = $request->except(['permissions']);
            $permissions = $request['permissions'];
            $role->fill($input)->save();

            $p_all = Permission::all(); //Get all permissions

            foreach ($p_all as $p) {
                $role->revokePermissionTo($p); //Remove all permissions associated with role
            }

            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
                $role->givePermissionTo($p);  //Assign permission to role
            }

            return ['status' => 'success', 'role' => $role];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteRole($id)
    {
        try {
            $this->roleRepository->delete($id); //Get role with the given id
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getRole($editId)
    {
        $role = $this->roleRepository->show($editId);

        $temp['id'] = $role->id;
        $temp['name'] = $role->name;
        $temp['permissions'] = $role->permissions()->pluck('id')->toArray();

        return $temp;
    }
}