<?php

namespace App\Repositories;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{

    public function __construct(protected Model|Builder $model)
    {
    }

    public function create(array $data): Model|Builder
    {
        return $this->model->create($data);
    }

    public function get(Closure $filter, array $columns = ['*'])
    {
        $result = $this->model->query();
        $result = $filter($result);
        if (empty($data['page'])) {
            $result = $result->get($columns);
        } else {
            $result = $result->paginate(perPage: $data['size'], columns: $columns, page: $data['page']);
        }

        return $result;
    }

    public function first(int $id): Model|Builder|null
    {
        return $this->model->where('id', $id)
            ->first();
    }

    public function findBy(string $column, mixed $value): Model|Builder|null
    {
        return $this->model->where($column, $value)
            ->first();
    }

    public function update(int $id, array $data): bool|int
    {
        return $this->model
            ->where('id', $id)
            ->update($data);
    }

    public function delete(int $id)
    {
        return $this->model
            ->where('id', $id)
            ->delete();
    }

    public function all(array $columns = ['*']): array|Collection
    {
        return $this->model->get($columns);
    }
}
