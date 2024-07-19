<?php

namespace App\Console\Commands;

use App\Jobs\UpdateClickCountsJob;
use App\Repositories\Contracts\LinkRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class InsertClickCountsCommand extends Command
{
    public function __construct(protected LinkRepositoryInterface $linkRepository)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link:count-clicks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $links = $this->linkRepository->all(['id', 'url', 'click_counts'])->toArray();
        foreach ($links as $link) {
            $key = 'link_count_' . base64_encode($link['url']);
            if (!Cache::has($key)) {
                continue;
            }
            dispatch(new UpdateClickCountsJob($link));
        }

        return self::SUCCESS;
    }
}
