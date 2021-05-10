# spatie-crawler-queue-with-laravel-model
[Spatie's Crawler](https://github.com/spatie/crawler) with Laravel Model as Queue

This is just a laravel application 8.x with a model class, a queue class, a migration class and a command class to use Spatie's Crawler package.

### Why this is better than others spatie/crawler queues packages?
> The main reason is the others queues packages store all items in one single array, which can be a RAM problem for big sites.
> Furthermore, you can preserve and use crawled links as you want

> To expire items we use [mvdnbrk/laravel-model-expires](https://github.com/mvdnbrk/laravel-model-expires)

> Processed items are marked as [soft-deleted](https://laravel.com/docs/8.x/eloquent#soft-deleting)

# Steps
1. Clone the repo
2. Run `composer install`
3. Run `php artisan migration` (after configure `database.php`)
4. Adjust `app/Console/Commands/CrawlerRun.php`
5. Run `php artisan craw https://site_or_blog.com`

Main files to take a look:
* [`app/Console/Commands/CrawlerRun.php`](app/Console/Commands/CrawlerRun.php)
* [`app/Models/CrawlerQueue.php`](app/Models/CrawlerQueue.php)
* [`app/Observers/Crawler/ConsoleObserver.php`](app/Observers/Crawler/ConsoleObserver.php)
* [`database/migrations/2021_05_07_041816_crawler_queues.php`](database/migrations/2021_05_07_041816_crawler_queues.php)
