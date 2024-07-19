<?php

namespace App\Repositories\Eloquents;

use App\Models\Link;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\LinkRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LinkRepository extends BaseRepository implements LinkRepositoryInterface
{
    public function __construct(Link $link)
    {
        parent::__construct($link);
    }

    public function incrementCountClicks(int $id, int $incrementNumber): bool|int
    {
        return $this->model->where('id', $id)
            ->increment('click_counts', $incrementNumber);
    }
}
