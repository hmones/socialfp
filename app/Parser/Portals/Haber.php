<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Haber extends ContentParser
{
//article:published_time
//
   function date()
    {
        $date = null;
        $element = $this->crawler->filter('meta[property="article:published_time"]');
        if (count($element) > 0) {
            $dateText = trim($element->attr('content'));
            $date=substr($dateText,0,10);
        }

        return $date;
    }
}
