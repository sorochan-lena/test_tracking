<?php

namespace App\Repositories;

use App\BadDomain;
use App\Contracts\Repositories\IBadDomainRepository;

class BadDomainRepository extends AbstractRepository implements IBadDomainRepository
{
    public function __construct(BadDomain $model)
    {
        parent::__construct($model);
    }
}
