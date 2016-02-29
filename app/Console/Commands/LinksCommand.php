<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\LinksExtractor;

class LinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:extract {url}';

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
        $urls = LinksExtractor::start($this->argument('url'),
        ['http://www.radiosarajevo.ba/home/kategorija/1','http://www.radiosarajevo.ba/home/kategorija/212']
        ,10);
        foreach($urls as $url){
            echo $url . "\n";
        }
    }
}
