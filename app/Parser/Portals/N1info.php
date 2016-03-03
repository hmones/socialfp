<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class N1info extends ContentParser
{


    function date()
    {
        $date = null;
        $element = $this->crawler->filter('article time .date');
        if (count($element) > 0) {
            $dateText = trim($element->text());
            $dateText = explode('.', $dateText);

            $day = $dateText[2];
            $month = $dateText[1];
            $year = $dateText[0];

            $date = "${year}-${month}-${day}";
        }

        return $date;
    }

}
