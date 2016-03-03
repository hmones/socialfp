<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Sportsport extends ContentParser
{

    public function date()
    {

        $date = null;
        $element = $this->crawler->filter('div[id=news_page] article time');
        if (count($element) > 0) {

            $dateText = $element->attr('datetime');
            $date = substr($dateText,0,10);
        }

        return $date;
    }

}
