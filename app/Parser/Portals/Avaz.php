<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Avaz extends ContentParser
{

    function date()
    {
        $date = null;
        $dateText = $this->crawler->filter('div[class="box article"] div[class=misc] span')->text();
        if ($dateText != null) {
            $dateText = preg_replace("/[^0-9]/", "", $dateText);
            $date = substr($dateText, 4, 4) . '-' . substr($dateText, 2, 2) . '-' . substr($dateText, 0, 2);
        }

        return $date;
    }

}
