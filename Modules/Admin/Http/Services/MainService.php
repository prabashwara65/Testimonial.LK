<?php

namespace Modules\Admin\Http\Services;

use Modules\Admin\Http\Traits\TableViewHelperTrait;

class MainService
{
    use TableViewHelperTrait;

    public function __construct()
    {
    }

    function generateWhereLikeQuery($model, $columns, $search)
    {
        $i = 0;
        foreach ($columns as $key => $column) {
            if ($key == 'Actions' || $key == 'Actions@no-sort@' || $column == '') {
                continue;
            }

            if ($i == 0) {
                $model->where($column, 'LIKE', '%'.$search.'%');
            } else {
                $model->orWhere($column, 'LIKE', '%'.$search.'%');
            }
            $i++;
        }

        return $model;
    }
}