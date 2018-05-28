<?php

namespace App\Repositories;

use App\Contracts\Repositories\IRepository;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements IRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * AbstractRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return string
     */
    public function create(array $attributes) : string
    {
        $model = $this->model->create($attributes);

        return $model->id;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function findByAttributes(array $attributes) : Model
    {
        return $this->model->where($attributes)->firstOrFail();
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return int
     */
    public function update(string $id, array $attributes) : int
    {
        return $this->model->where('id', $id)->update($attributes);
    }

    /**
     * @param string $id
     * @return Model
     */
    public function findOrFail(string $id) : Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->model::all();
    }
}