<?php

namespace App\Observers\Crawler;

use App\Models\CrawlerQueue;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver as SpatieCrawlObserver;


class ConsoleObserver extends SpatieCrawlObserver
{

    public function __construct(\Illuminate\Console\Command $console)
    {
        $this->console = $console;
    }

    /**
     * @param UriInterface $url
     */
    public function willCrawl(UriInterface $url): void
    {
        $this->console->comment("Found: {$url}");
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = NULL): void
    {
        $this->console->total_crawled++;

        // item acabou de ser arquivado, mas não expirado.
        $item = CrawlerQueue::onlyTrashed()->url($url)->first();

        if ($item->count()) {
            $item->html = $response->getBody();

            $item->save();
        }

        $this->console->info("Crawled: ({$this->console->total_crawled}) {$url} ({$foundOnUrl})");
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = NULL): void
    {
        $this->console->error("Fail: {$url}. {$requestException->getMessage()}");
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
        $this->console->info('Crawler: Finished');
    }
}
