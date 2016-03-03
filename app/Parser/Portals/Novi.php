<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Novi extends ContentParser
{

    public function date()
    {

        $date = null;
        $element = $this->crawler->filter('.article-view  .misc .info span')->first();
        if (count($element) > 0) {

            $dateText = $element->text();
            $dateText = preg_replace('/[^0-9]/', "", $dateText);
            $date = substr($dateText, 4, 4) . '-' . substr($dateText, 2, 2) . '-' . substr($dateText, 0, 2);
        }

        return $date;
    }

}
