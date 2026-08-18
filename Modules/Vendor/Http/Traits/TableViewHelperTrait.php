<?php

namespace Modules\Vendor\Http\Traits;

use Illuminate\Support\Facades\View;

trait TableViewHelperTrait {
    public function generateActionButtons($route, $id, $permissions, $view = 'common.table-action-buttons'){
        return View::make($view, ['id' => $id, 'route' => $route, 'permissions' => $permissions])->render();
    }

    public function generateCustomerButtons($route, $id, $count, $view = 'vendor::customers.table-customer-buttons'){
        return View::make($view, ['id' => $id, 'route' => $route, 'count' => $count])->render();
    }
}