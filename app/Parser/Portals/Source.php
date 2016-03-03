<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Source extends ContentParser
{

    public function date()
    {

        $date = null;
        $element = $this->crawler->filter('div[class=vrijemeObjaveClanka]');
        if (count($element) > 0) {
            $dateText = trim($element->text());
            $dateText = explode(' ', $dateText)[0];
            $dateText = explode('.', $dateText);

            $day = $dateText[0];
            $month = $dateText[1];
            $year = $dateText[2];

            $date = $year . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $day);
        }

        return $date;
    }

}
