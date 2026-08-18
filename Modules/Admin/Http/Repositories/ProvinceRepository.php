<?php

namespace Modules\Admin\Http\Repositories;

use App\Models\Province;
use ErrorException;

class ProvinceRepository extends Repository
{
    public function __construct(Province $province)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($province);
    }

    // remove record from the database
    public function delete($id)
    {
        try {
            $record = $this->model->findOrFail($id);

            if ($record->districts()->count() == 0) {
                $record->delete();
            } else {
                $count = $record->districts()->count();
                throw new ErrorException('This can\'t be delete.<br>'.$count.' district/s have already used it.');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
