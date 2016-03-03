<?php
namespace App;

use Illuminate\Support\Collection;
use Goutte\Client;
use ReflectionClass;
use Log;
use Config;



class Scrapper{
    private $client;
    private $crawler;

    public function __construct(){
        $this->client = new Client();
    }

    public function scrap($url){
        $this->crawler = $this->client->request('GET', $url);
        $parserName = self::matchParser($url);
        $parser = self::createParser($parserName, $this->crawler, $url);
        return $parser;

    }

    public function crawler(){
        return $this->crawler;
    }

    /** throw exception on Error */
    public static function matchParser($pageUrl){
        $parser = null;
        foreach(Config::get('portals') as $url => $parserName){
            if(starts_with($pageUrl, $url)){
                $parser = $parserName;
            }
        }

        return $parser;
    }

    public static function createParser($parserName, $crawler, $url){
        // if($parserName == null) throw new Exception('no parser');
        $class = new ReflectionClass($parserName);
        $parser = $class->newInstanceArgs(array($crawler, $url));
        return $parser;
    }


};
