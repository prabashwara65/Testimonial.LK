<?php

namespace Modules\Vendor\Http\Services;

use Illuminate\Support\Facades\DB;

use App\Models\ActionLog;

use App\Http\Constants\Actions;

class ActionLogService extends MainService
{
    private $roleRepository;

    public function getPaginated($columns, $offset, $limit, $search, $orderBy, $orderDirection, $filterForm)
    {
        $actionLog = ActionLog::where('vendor_company_id', auth()->user()->vendor_company_id)
            ->whereRaw("DATE(created_at) BETWEEN '" . $filterForm['start_date'] . "' AND '" . $filterForm['end_date'] . "' ");

        if (!empty($search)) {
            $actionLog->where(function ($query) use ($columns, $search) {
                $query = $this->generateWhereLikeQuery($query, $columns, $search);
            });
        }
        if (!empty($orderBy)) {
            $actionLog->orderBy($orderBy, $orderDirection);
        } else {
            $actionLog->orderBy('created_at', 'desc');
        }

        if ($filterForm['user'] != 'Any') {
            $actionLog->where('user', 'LIKE', '%' . $filterForm['user'] . '%');
        }
        if ($filterForm['permission'] != 'Any') {
            $actionLog->where('action', $filterForm['permission']);
        }

        /// get the filtered row count before limiting the results
        $rows = $actionLog->get();
        $count = count($rows);

        // limit the results for pagination
        $actionLog->offset($offset)->limit($limit);
        $rows = $actionLog->get();

        $data = [];
        foreach ($rows as $log) {
            $temp = [
                $log->user,
                $log->ip,
                $log->action,
                $log->created_at->format('Y-m-d h:i A'),
                // if you want to customize the buttons, use a blade template
                // and copy the default action button template at "common/table-action-buttons.blade.php"
                // and pass the new custom template name as the 3rd parameter
                $this->generateActionButtons('vendor.action-log', $log->id, ['view' => Actions::EDIT_USERS])
            ];
            array_push($data, $temp);
        }

        $out['data'] = $data;
        $out['recordsFiltered'] = $count; // count of records after applying search filters
        $out['recordsTotal'] = ActionLog::count(); // count of all the records in the database table

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
