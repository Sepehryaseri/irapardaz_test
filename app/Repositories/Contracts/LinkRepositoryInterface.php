<?php

namespace App\Repositories\Contracts;

use App\Repositories\BaseRepositoryInterface;

interface LinkRepositoryInterface extends BaseRepositoryInterface
{
    public function incrementCountClicks(int $id, int $incrementNumber);
}
