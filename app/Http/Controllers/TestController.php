<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $url = $request->get('url');

        if (!is_null($url)) {
            // Get or create a new array queue.
            $queue = Cache::remember(md5($url), 3600, function() { return new \Spatie\Crawler\CrawlQueues\ArrayCrawlQueue; });

            // Alternatively, this queue stores the data directly in the cache
            // $queue = new \Spekulatius\SpatieCrawlerToolkit\Queues\CacheCrawlQueue($url);

            Crawler::create()
                ->setCurrentCrawlLimit(5)
                ->setDelayBetweenRequests(150)
                ->setCrawlQueue($queue)
                ->setCrawlProfile(new \Spatie\Crawler\CrawlProfiles\CrawlSubdomains($url))
                ->addCrawlObserver(new \Spekulatius\SpatieCrawlerToolkit\Observers\CrawlLogger)
                ->startCrawling($url);

            // Store queue in cache
            Cache::put(md5($url), $queue, 3600);
        }

        return view('test', [
            'url' => $url,
            'log' => @file_get_contents(storage_path('logs/laravel.log')),
        ]);
    }
}
