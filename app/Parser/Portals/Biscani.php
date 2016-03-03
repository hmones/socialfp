<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Biscani extends ContentParser
{


    function date()
    {
        $date = null;
        $element = $this->crawler->filter('div[id=post-info22] a');
        if (count($element) > 0) {
            $dateText = trim(explode('/',$element->text())[1]);
            $items = preg_split('/[\s\.]/', trim($dateText), -1, PREG_SPLIT_NO_EMPTY);
            $day = $items[0];
            $month = self::month_trans($items[1]);
            $year = $items[2];
            $date = $year . '-' . $month . '-' . sprintf("%02d", $day);

            return $dateText;
        }

        return $date;
    }

}
