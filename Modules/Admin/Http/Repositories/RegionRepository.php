<?php

namespace Modules\Admin\Http\Repositories;

use App\Models\Region;
use ErrorException;

class RegionRepository extends Repository
{
    public function __construct(Region $region)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($region);
    }

    // remove record from the database
    public function delete($id)
    {
        try {
            $record = $this->model->findOrFail($id);

            if ($record->countries()->count() == 0) {
                $record->delete();
            } else {
                $count = $record->countries()->count();
                throw new ErrorException('This can\'t be delete.<br>'.$count.' country/s have already used it.');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
