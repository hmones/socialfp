<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Aljazeera extends ContentParser
{

    public function __construct($crawler)
    {
        parent::__construct($crawler);
    }

    public function date()
    {

        $date = null;

        $element = $this->crawler->filter('meta[property="og:updated_time"]');

        if (count($element) > 0) {
            $dateText = $element->attr('content');
            $date = substr($dateText, 0, 10);
            return $date;
        }

        $element = $this->crawler->filter('article time');
        if (count($element) > 0) {
            $dateText = $element->text();
            $items = preg_split('/[\s\.]/', trim($dateText), -1, PREG_SPLIT_NO_EMPTY);
            $day = $items[0];
            $month = self::month_trans($items[1]);
            $year = $items[2];
            $date = $year . '-' . $month . '-' . sprintf("%02d", $day);
        }

        return $date;
    }

    public function shares()
    {
        return "shares";
    }

}
