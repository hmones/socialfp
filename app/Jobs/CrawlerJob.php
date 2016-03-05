<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Result;
use App\Scrapper;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Spatie\Crawler\Url;
use DB;

class CrawlerJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $url;
    private $depth;
    const SESSION_TIMEOUT = 60;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $depth = 4)
    {
        $this->url = $url;
        $this->depth = $depth;
    }

    public function handle()
    {

        if ($this->depth < 0) {
            Log::debug('Depth of process reached the allowed limit. Terminating...');
            return;
        }

        /** check if already processed in this session (60 Minutes) */
        if ($this->alreadyProcessed($this->url)) {
            Log::debug('Already Processed ' . $this->url);
            return;
        }

        /** Alread extracted and stored **/
        $exists = $this->alreadyStored($this->url);


        /** start scrapping **/
        $scrapper = new Scrapper();
        $parser = $scrapper->scrap($this->url);
        $crawler = $scrapper->crawler();

        if ($parser->isArticle() && $parser->date() != null) {
            if ($exists) {
                $this->updatePage($parser);
            } else {
                $this->savePage($parser);
            }

        }

        /** save in this session cahse for 60 minutes **/
        $this->markAsProcessed($this->url);
        if ($this->depth > 0) {

            $allLinks = $this->getAllLinks($crawler);
            $this->crawlAllLinks($allLinks);
        }

    }

    public function alreadyProcessed($url)
    {
        $urls = Cache::get('PROCESSED_URLS', []);
        if (in_array((string) $url, $urls)) {
            return true;
        }
        return false;
    }

    public function alreadyStored($url)
    {
        $urls = $this->getSavedLinkes($url);
        if (in_array((string) $url, $urls)) {
            return true;
        }
        return false;
    }

    public function getSavedLinkes($url)
    {
        $url = Url::create($url);
        $key = 'PORTAL_' . $url->host;
        $storedLinks = Cache::remember($key, 1, function () use ($url) {
            $result = DB::select('select url from results where portal = ?', [$url->host]);
            Log::debug('Found ' . count($result) . ' stored pages for Portal: ' . $url->host);

            return $result;
        });

        return $storedLinks;

    }

    public function markAsProcessed($url)
    {
        $urls = Cache::get('PROCESSED_URLS', []);
        array_push($urls, (string) $url);
        Cache::put('PROCESSED_URLS', $urls, self::SESSION_TIMEOUT);
    }

    public function savePage($parser)
    {
        Log::debug('Saving parsed Url ' . $this->url);

        $result = new Result();
        $result->url = $this->url;
        $result->date = $parser->date();
        $result->isArticle = $parser->isArticle();
        $result->page_title = $parser->title();

        $result->content = $parser->content();
        $result->description = $parser->description();
        $result->portal = Url::create($this->url)->host;
        //$result->last_update = date("Y/m/d h:i:s");

        $socialshares = $parser->social();
        $result->fb_likes = $socialshares['fb_likes'];
        $result->fb_shares = $socialshares['fb_shares'];
        $result->fb_comments = $socialshares['fb_comments'];
        $result->gp_shares = $socialshares['gp_shares'];
        $result->total_shares = $socialshares['fb_likes'] + $socialshares['fb_shares'] + $socialshares['fb_comments'] + $socialshares['gp_shares'];

        $result->save();
    }

    public function updatePage($parser)
    {
        Log::debug('Updating parsed Url ' . $this->url);

        $result = Result::get('url', $this->url);

        $socialshares = $parser->social();
        $result->fb_likes = $socialshares['fb_likes'];
        $result->fb_shares = $socialshares['fb_shares'];
        $result->fb_comments = $socialshares['fb_comments'];
        $result->gp_shares = $socialshares['gp_shares'];
        $result->total_shares = $socialshares['fb_likes'] + $socialshares['fb_shares'] + $socialshares['fb_comments'] + $socialshares['gp_shares'];

        $result->save();
    }

    public function getAllLinks($domCrawler)
    {
        return collect($domCrawler->filterXpath('//a')->extract(['href']))
            ->map(function ($url) {
                return Url::create($url);
            });
    }

    public function crawlAllLinks($links)
    {
        Log::debug('Found ' . count($links) . ' in page ' . $this->url);
        $flteredLinks = collect($links)
            ->filter(function (Url $url) {
                return !$url->isEmailUrl();
            })
            ->map(function (Url $url) {
                return $this->normalizeUrl($url);
            })
            ->filter(function (Url $url) {
                return !$this->alreadyProcessed($url);
            })
            ->filter(function (Url $url) {
                return $url->host == Url::create($this->url)->host;
            });

            Log::debug('Crawling ' . count($flteredLinks) . ' in page ' . $this->url);
            $flteredLinks->each(function (Url $url) {
                Log::debug('Creating a CrawlerJob(' . ($this->depth - 1) . ') for Url ' . $url);
                dispatch(new CrawlerJob((string) $url, $this->depth - 1));
            });
    }

/**
 * Normalize the given url.
 *
 * @param \Spatie\Crawler\Url $url
 *
 * @return $this
 */
    public function normalizeUrl(Url $url)
    {
        $baseUrl = Url::create($this->url);
        if ($url->isRelative()) {

            $url->setScheme($baseUrl->scheme)
                ->setHost($baseUrl->host)
                ->setPort($baseUrl->port);
        }

        if ($url->isProtocolIndependent()) {
            $url->setScheme($baseUrl->scheme);
        }

        return $url->removeFragment();
    }

    public function __toString()
    {
        'Crawling URL ' . $this->url;
    }
}
