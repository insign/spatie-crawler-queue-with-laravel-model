<?php

namespace App\Queues;

use App\Models\CrawlerQueue;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlQueues\CrawlQueue;
use Spatie\Crawler\CrawlUrl;
use Spatie\Crawler\Exceptions\InvalidUrl;
use Spatie\Crawler\Exceptions\UrlNotFoundByIndex;

class CrawlerCacheQueue implements CrawlQueue
{

    /**
     * Define expiry of cached URLs.
     *
     * @var int|null
     */
    protected mixed $ttl = NULL;

    /**
     * Defines an instance of the CacheQueue
     *
     * @param int|null $ttl
     */
    public function __construct(int $ttl = NULL)
    {
        $this->ttl = $ttl ?? config('crawler.cache.ttl', 86400); // one day
    }

    /**
     * Adds a new URL to the queue (and cache).
     *
     * @param CrawlUrl $crawlUrl
     * @return CrawlQueue
     */
    public function add(CrawlUrl $crawlUrl): CrawlQueue
    {
        if (!$this->has($crawlUrl)) {
            $crawlUrl->setId((string) $crawlUrl->url);

            $item = new CrawlerQueue;

            $item->url_class  = $crawlUrl;
            $item->expires_at = $this->ttl;

            $item->save();
        }

        return $this;
    }

    /**
     * Marks the given URL as processed
     *
     * @param CrawlUrl $crawlUrl
     * @return void
     */
    public function markAsProcessed(CrawlUrl $crawlUrl): void
    {
        // @OBS deleted_at = soft delete = processado
        CrawlerQueue::url($crawlUrl)->delete();
    }

    public function getPendingUrl(): ?CrawlUrl
    {
        // Any URLs left?
        if ($this->hasPendingUrls()) {
            $random = CrawlerQueue::inRandomOrder()->first();

            return $random->url_class;
        }

        return NULL;
    }

    public function has(UriInterface|CrawlUrl|string $crawlUrl): bool
    {
        return (bool) CrawlerQueue::withTrashed()->url($crawlUrl)->count();
    }

    public function hasPendingUrls(): bool
    {
        return (bool) CrawlerQueue::count();
    }

    public function getUrlById($id): CrawlUrl
    {
        if (!$this->has($id)) {
            throw new UrlNotFoundByIndex("Crawl url {$id} not found in collection.");
        }
        $item = CrawlerQueue::withTrashed()->url($id)->first();

        return $item->url_class;
    }

    public function hasAlreadyBeenProcessed(CrawlUrl $crawlUrl): bool
    {
        $inQueue   = (bool) CrawlerQueue::url($crawlUrl)->count();
        $processed = (bool) CrawlerQueue::onlyTrashed()->url($crawlUrl)->count();

        if ($inQueue) {
            return FALSE;
        }

        if ($processed) {
            return TRUE;
        }

        return FALSE;

    }

    public function getProcessedUrlCount(): int
    {
        $processed = CrawlerQueue::onlyTrashed()->count();
        $pending   = CrawlerQueue::count();

        return $processed - $pending;
    }
}
