<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class RadioSarajevo extends ContentParser
{



    public function date()
    {
        $date = null;
        $dateText = $this->crawler->filter('div[class=news_date]')->text();
        if ($dateText != null) {
            $items = preg_split('/[\s\.]/', trim($dateText), -1, PREG_SPLIT_NO_EMPTY);

            $day = $items[0];
            $month = self::month_trans($items[1]);
            $year = $items[2];
            $date = $year . '-' . $month . '-' . $day;
        }

        return $date;
    }

}
