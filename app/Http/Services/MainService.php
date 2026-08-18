<?php

namespace App\Http\Services;

use Modules\Vendor\Http\Traits\TableViewHelperTrait;

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

    function export($model, $columnsFilter, $searchValue, $tableName)
    {
        $fileName = "EXPORT_" . date("Ymd") . '.csv';
        if (!empty($searchValue)) {
            $i = 0;
            foreach ($columnsFilter as $key => $column) {
                if ($i == 0) {
                    $model->having($column, 'LIKE', '%' . $searchValue . '%');
                } else {
                    $model->orHaving($column, 'LIKE', '%' . $searchValue . '%');
                }
                $i++;
            }
        }
        $model = $model->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($model, $columnsFilter) {
            $file = fopen('php://output', 'w');
            $columnHeader = array();
            foreach ($columnsFilter as $co) {
                $replace = ucwords(str_replace("_", " ", $co));
                array_push($columnHeader, $replace);
            }
            fputcsv($file, $columnHeader);
            foreach ($model as $data) {
                $ar = array();
                foreach ($columnsFilter as $c) {
                    array_push($ar, $data->$c);
                }
                fputcsv($file, $ar);
            }
            fclose($file);
        };
        return array($callback, $headers);
    }
}