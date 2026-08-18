<?php

namespace Modules\Vendor\Http\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExcelExport implements FromCollection, WithHeadings, WithMapping
{

    protected $columnHeaders;
    protected $tableColumns;
    protected $data;

    public function __construct($columnHeaders, $tableColumns, $data)
    {
        $this->columnHeaders = $columnHeaders;
        $this->tableColumns = $tableColumns;
        $this->data = $data;
    }

    public function headings(): array
    {
        return $this->columnHeaders;
    }

    public function map($row): array{
        $fields = [];
        foreach ($this->tableColumns as $column) {
            array_push($fields, $row->$column);
        }
        return $fields;
    }

    public function collection()
    {
        return $this->data;
    }

}
