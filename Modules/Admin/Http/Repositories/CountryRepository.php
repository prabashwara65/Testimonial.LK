<?php

namespace Modules\Admin\Http\Repositories;

use App\Models\Country;
use ErrorException;

class CountryRepository extends Repository
{
    public function __construct(Country $country)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($country);
    }

    // remove record from the database
    public function delete($id)
    {
        try {
            $record = $this->model->findOrFail($id);

            if ($record->provinces()->count() == 0) {
                $record->delete();
            } else {
                $count = $record->provinces()->count();
                throw new ErrorException('This can\'t be delete.<br>'.$count.' province/s have already used it.');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
