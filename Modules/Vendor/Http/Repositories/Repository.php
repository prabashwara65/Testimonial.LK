<?php

namespace Modules\Vendor\Http\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model
    public function all()
    {
        try {
            return $this->model->all();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // create a new record in the database
    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // update record in the database
    public function update(array $data, $id)
    {
        try {
            $record = $this->find($id);
            return $record->update($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // remove record from the database
    public function delete($id)
    {
        try {
            return $this->model->destroy($id);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // show the record with the given id
    public function show($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        try {
            return $this->model->with($relations);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}