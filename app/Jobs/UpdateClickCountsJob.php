<?php

namespace App\Jobs;

use App\Repositories\Contracts\LinkRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateClickCountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $link)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(LinkRepositoryInterface $linkRepository): void
    {
        $shortLink = base64_encode($this->link['url']);
        $redisKey = 'link_count_' . $shortLink;
        $cachedCountClicks = Cache::get($redisKey);
        $linkRepository->incrementCountClicks($this->link['id'], $cachedCountClicks);
        Cache::delete($redisKey);
    }
}
