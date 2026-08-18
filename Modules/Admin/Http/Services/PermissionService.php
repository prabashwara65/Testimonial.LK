<?php
namespace Modules\Admin\Http\Services;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;

use Modules\Admin\Http\Repositories\PermissionRepository;

use App\Http\Constants\Actions;

class PermissionService extends MainService
{
    private $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection)
    {
        // * customize start
        $model = Permission::select();
        // * customize end

        if (!empty($search)) {
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
                $row->id,
                $row->name,
                $row->guard_name,
                $row->roles->pluck('name')->implode(", "),
                // * customize end

                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('admin.permissions', $row->id, ['view' => false, 'edit' => Actions::EDIT_PERMISSIONS, 'delete' => false])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = Permission::count(); // count of all the records in the database table

        return $out;
    }

    public function create(Request $request)
    {
        try {
            $guard_name = $request['guard_name'];
            $name = $request['name'];

            $permission = Permission::create(['guard_name' => $guard_name, 'name' => $name]);

            return ['status' => 'success', 'permission' => $permission];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($request, $id)
    {
        try {
            $permission = $this->repository->show($id);
            $permission->name = $request['name'];
            $permission->guard_name = $request['guard_name'];
            $permission->save();

            return ['status' => 'success', 'permission' => $permission];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id); //Get role with the given id
            return ['status' => 'success'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function get($editId)
    {
        $row = $this->repository->show($editId);

        $temp['id'] = $row->id;
        $temp['name'] = $row->name;
        $temp['guard_name'] = $row->guard_name;

        return $temp;
    }
}