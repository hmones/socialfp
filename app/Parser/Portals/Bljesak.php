<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Bljesak extends ContentParser
{

    public function __construct($crawler)
    {
        parent::__construct($crawler);
    }

    public function date()
    {

        $date = null;
        $element = $this->crawler->filter('div[class=info] div');
        if (count($element) > 0) {
            $dateText = explode(',', $element->text())[1];
            $dateText=preg_replace('/(Č|č)/','c',$dateText);
            $dateText=preg_replace('/(Ž|ž)/','z',$dateText);
            $items = preg_split('/[\s\.]/', trim($dateText), -1, PREG_SPLIT_NO_EMPTY);
            $day = $items[0];
            $month = self::month_trans($items[1], 'CR');
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
