<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SitemapGenerator;

class SitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl all links in a given website';

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
     * @return mixed
     */
    public function handle()
    {
        $urls = SitemapGenerator::start($this->argument('url'),[],10);
        dd($urls);
    }
}
