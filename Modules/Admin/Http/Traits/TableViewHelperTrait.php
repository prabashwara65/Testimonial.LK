<?php

namespace Modules\Admin\Http\Traits;

use Illuminate\Support\Facades\View;

trait TableViewHelperTrait {
    public function generateActionButtons($route, $id, $permissions, $view = 'common.table-action-buttons'){
        return View::make($view, ['id' => $id, 'route' => $route, 'permissions' => $permissions])->render();
    }
}