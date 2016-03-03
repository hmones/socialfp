<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Ekskluziva extends ContentParser
{


    function date()
    {

        $date = null;
        $element = $this->crawler->filter('div[class="boxNaslov clanaknaslov"] div[class=datum]');
        if (count($element) > 0) {
            $dateText = trim($element->text());
            $date='20'.substr($dateText,6,2).'-'.substr($dateText,3,2).'-'.substr($dateText,0,2);
        }

        return $date;
    }

}
