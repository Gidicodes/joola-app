<?php

namespace App\Repositories;


class BaseRepository{

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function create(array $data):object
    {
        return $this->model->create($data);
    }

    /**
     * Update  Model
     * @return Model object
     */
    public function update(object $modelObject, array $data)
    {
        $modelObject->update($data);
        return $modelObject;
    }

    /**
     * Returns object by id
     * @return Model object
     */
    public function get(int $id){
        return $this->model->findOrFail($id);
    }


    /**
     * Returns all object data
     * @return Collection
     */
    public function all()
    {
        return $this->model::get();
    }


    /**
     * Deletes object from collection
     * @return null
     */

     public function delete(int $id){
         return $this->get($id)->delete();
     }



}