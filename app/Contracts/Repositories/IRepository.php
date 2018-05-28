<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface IRepository {
    /**
     * @param array $attributes
     * @return string
     */
    public function create(array $attributes) : string;

    /**
     * @param array $attributes
     * @return Model
     */
    public function findByAttributes(array $attributes) : Model;

    /**
     * @param string $id
     * @param array $attributes
     * @return int
     */
    public function update(string $id, array $attributes) : int;

    /**
     * @param string $id
     * @return Model
     */
    public function findOrFail(string $id) : Model;

    /**
     * @return array
     */
    public function all();
}