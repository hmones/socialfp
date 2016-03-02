<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Klix extends ContentParser
{

    public function __construct($crawler)
    {
        parent::__construct($crawler);
    }


    function date()
    {
        $date = null;
        $element = $this->crawler->filter('meta[name="twitter:url"]');
        if (count($element) > 0) {
            $dateText = $element->attr('content');
            $year='20'.substr($dateText,-9,-7);
            $month=substr($dateText,-7,-5);
            $day=substr($dateText,-5,-3);
            $date=$year.'-'.$month.'-'.$day;
        }

        return $date;
    }

    function shares()
    {
        return "shares";
    }

}
