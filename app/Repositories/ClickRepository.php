<?php

namespace App\Repositories;

use App\Click;
use App\Contracts\Repositories\IClickRepository;

class ClickRepository extends AbstractRepository implements IClickRepository
{
    public function __construct(Click $model)
    {
        parent::__construct($model);
    }
}