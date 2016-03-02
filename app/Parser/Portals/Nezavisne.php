<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Nezavisne extends ContentParser
{

    public function __construct($crawler)
    {
        parent::__construct($crawler);
    }


    function date()
    {

        $date = null;
        $element = $this->crawler->filter('time[class="dateline text-muted"]');
        if (count($element) > 0) {
            $dateText = $element->text();
            // dd($dateText);
            $day = substr($dateText,0,2);

            $month = substr($dateText,3,2);
            $year = substr($dateText,6,4);
            
            $date = "${year}-${month}-${day}";
        }

        return $date;
    }

    function shares()
    {
        return "shares";
    }

}
