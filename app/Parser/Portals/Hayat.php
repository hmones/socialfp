<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Hayat extends ContentParser
{

    public function date()
    {

        $date = null;
        $element = $this->crawler->filter('div[id=content] div[class=date]');
        if (count($element) > 0) {
            $dateText = trim($element->text());

            $day = preg_replace('/[^0-9]/', "", $dateText);
            $day = substr($day, 0, -4);
            if (strlen($day) == 1) {
                $day = '0' . $day;
            }

            $year = date('Y');
            $month = preg_replace('/[^A-z]/', "", $dateText);
            $date = $year . '-' . $this->month_trans($month, 'EN') . '-' . $day;

        }

        return $date;
    }
}
