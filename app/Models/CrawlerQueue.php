<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mvdnbrk\EloquentExpirable\Expirable;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlUrl;

class CrawlerQueue extends Model
{
    // @OBS deleted_at = soft delete = processed
    use Expirable, SoftDeletes;

    protected string $HASH_ALGO = 'sha3-512';


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('withoutExpired', function (Builder $builder) {
            $builder->withoutExpired();
        });
    }

    public function setHtmlAttribute(string $html): void
    {
        $html = preg_replace('/\R+/', ' ', $html);
        $html = json_encode($html);
        $this->attributes[ 'html' ] = $html;
    }

    public function getHtmlAttribute($html)
    {
        return json_decode($html);
    }

    /**
     *
     * @param UriInterface|CrawlUrl $crawlUrl
     * @return void
     */
    public function setUrlClassAttribute(UriInterface|CrawlUrl $crawlUrl): void
    {
        $url = (string) $crawlUrl->url;

        $this->attributes[ 'url' ]       = $url;
        $this->attributes[ 'url_hash' ]  = hash($this->HASH_ALGO, $url);
        $this->attributes[ 'url_class' ] = serialize($crawlUrl);
    }

    public function getUrlClassAttribute($crawlUrl)
    {
        return unserialize($crawlUrl, [ 'allowed_classes' => TRUE, ]);
    }

    /**
     * Search by url hash.
     *
     * @param Builder $query
     * @param UriInterface|CrawlUrl|string $crawlUrl
     * @return Builder
     */
    public function scopeUrl(Builder $query, UriInterface|CrawlUrl|string $crawlUrl): Builder
    {
        if ($crawlUrl instanceof CrawlUrl) {
            $urlString = (string) $crawlUrl->url;
        } elseif ($crawlUrl instanceof UriInterface) {
            $urlString = (string) $crawlUrl;
        } else { // string
            $urlString = $crawlUrl;
        }

        return $query->where('url_hash', hash($this->HASH_ALGO, $urlString));
    }
}
