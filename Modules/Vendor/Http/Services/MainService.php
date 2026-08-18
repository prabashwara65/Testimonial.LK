<?php

namespace Modules\Vendor\Http\Services;

use Modules\Vendor\Http\Traits\TableViewHelperTrait;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Vendor\Http\Exports\ExcelExport;

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

    function exportReport($type, $searchValue, $title, $columnHeaders, $tableColumns, $model)
    {
        if ($type == "EXCEL") {
            return Excel::download(new ExcelExport($columnHeaders, $tableColumns, $model), "EXPORT_" . date("Ymd") . '.xlsx');
        }
        elseif ($type == "PDF" || $type == "PRINT") {
            $data = [
                'title' => $title . " " . date("Y-m-d") . " Download",
                'heading' => $title,
                'filter_text' => $searchValue,
                'headers' => $columnHeaders,
                'columns' => $tableColumns,
                'data' => $model,
            ];
            $customPaper = array(0,0,500.00,1200.00);
            $pdf = PDF::loadView('common.print_pdf', $data)->setPaper($customPaper, 'landscape');;

            if ($type == "PRINT") {
                return $pdf->stream('branches' . date("Ymd") . '.pdf');
            } else {
                return $pdf->download('branches' . date("Ymd") . '.pdf');
            }
        }
        elseif ($type == "CSV") {
            $va = $this->exportCSV($model, $columnHeaders, $tableColumns);
            return response()->stream($va[0], 200, $va[1]);
        }
    }

    function exportCSV($model, $columnHeaders, $columnsFilter)
    {
        $fileName = "EXPORT_" . date("Ymd") . '.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($model, $columnHeaders, $columnsFilter) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columnHeaders);

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
