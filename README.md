# spatie-crawler-queue-with-laravel-model
Spatie's Crawler with Laravel Model

This is just a laravel application 8.x with a model class, a queue class, a migration class and a command class to use Spatie's Crawler package.

### Why this is better than others spatie/crawler queues packages?
> The main reason is the the queues store all items in one single array, which can be a RAM problem for big sites.

1. Clone the repo
2. Run `composer install`
3. Run `php artisan migration` (after configure `database.php`)
4. Adjust `app/Console/Commands/CrawlerRun.php`
5. Run `php artisan craw https://site_or_blog.com`

Main files to take a look:
* `app/Console/Commands/CrawlerRun.php`
* `app/Models/CrawlerQueue.php`
* `app/Observers/Crawler/ConsoleObserver.php`
* `Database/migrations/2021_05_07_041816_crawler_queues.php`