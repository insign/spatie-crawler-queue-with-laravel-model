<?php

namespace App\Console\Commands;

use App\Observers\Crawler\ConsoleObserver;
use App\Queues\CrawlerCacheQueue;
use Spatie\Crawler\Crawler;
use Illuminate\Console\Command;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;

class CrawlerRun extends Command
{
    public int $total_crawled = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craw {site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepares and runs the crawler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $queue = null;
        $site = $this->argument('site');

        if (is_null($queue)) {
            $this->info('Preparing a new crawler queue');

            $queue = new CrawlerCacheQueue(86400); // one day
        }

        // Crawler
        $this->info('Start crawling');

        Crawler::create()
            ->setParseableMimeTypes(['text/html', 'text/plain'])
            ->addCrawlObserver(new ConsoleObserver($this))
//            ->setCurrentCrawlLimit(200)
            ->setConcurrency(20)
            ->setCrawlQueue($queue)
            ->setCrawlProfile(new CrawlInternalUrls($site))
            ->startCrawling($site);

        $this->alert("Crawled {$this->total_crawled} items");

        if ($queue->hasPendingUrls()) {
            $this->alert('Has URLs left');
        } else {
            $this->info('Has no URLs left');
        }

        return 0;
    }
}
