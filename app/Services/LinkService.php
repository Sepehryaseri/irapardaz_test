<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\LinkRepositoryInterface;
use App\Traits\Exceptionable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class LinkService
{
    use Exceptionable;

    protected ?User $user;

    public function __construct(protected LinkRepositoryInterface $linkRepository)
    {
        $this->user = auth('sanctum')->user();
    }

    public function make(array $data): array
    {
        try {
            $data['user_id'] = $this->user->id;
            $link = $this->linkRepository->create($data);
            $hashedUrl = base64_encode($link->url);
            return [
                'message' => __('link.created'),
                'data' => [
                    'short_link' => $hashedUrl,
                ],
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function getMaxClicks(array $data): array
    {
        try {
            $links = $this->linkRepository->get(function (Builder $builder) use ($data) {
                return $builder->limit($data['limit'])
                    ->orderBy('click_counts', 'desc');
            });

            $links->each(function ($item) {
                $item->short_link = base64_encode($item->url);
            });

            return [
                'status' => Response::HTTP_OK,
                'data' => $links->toArray(),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function get(array $data): array
    {
        try {
            $links = $this->linkRepository->get(function (Builder $builder) use ($data) {
                return $builder->when(!empty($data['search']), function (Builder $query) use ($data) {
                    $query->where('url' , 'LIKE', "%".$data['search']."%");
                })
                    ->when(!empty($data['owner']) && $data['owner'] == 1, function (Builder $query) {
                        $query->where('user_id', $this->user->id);
                    });
            });

            $links->each(function ($item) {
                $item->short_link = base64_encode($item->url);
            });

            return [
                'status' => Response::HTTP_OK,
                'data' => $links->toArray(),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function getLink(string $shortLink): array
    {
        try {
            $url = base64_decode($shortLink);
            $link = $this->linkRepository->findBy('url', $url);
            if (is_null($link)) {
                throw new Exception(message: __('link.not_exists'));
            }
            $redisKey = 'link_count_' . $shortLink;
            if (Cache::has($redisKey)) {
                Cache::increment($redisKey);
            } else {
                Cache::set($redisKey, 1, 7200);
            }
            return [
                'status' => Response::HTTP_OK,
                'data' => $link->toArray()
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }
}
