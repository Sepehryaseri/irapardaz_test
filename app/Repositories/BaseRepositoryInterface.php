<?php

namespace App\Repositories;

use Closure;

interface BaseRepositoryInterface
{
    public function create(array $data);

    public function get(Closure $filter, array $columns = ['*']);

    public function first(int $id);

    public function findBy(string $column, mixed $value);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function all(array $columns = ['*']);
}
