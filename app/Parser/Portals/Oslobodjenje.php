<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Oslobodjenje extends ContentParser
{

    //DDS protection
    public function date()
    {

        $date = null;
        $element = $this->crawler->filter('div[id=noviarticle] p[class=author]');
        if (count($element) > 0) {
            $dateText = trim($element->text());
            $dateText = preg_replace('/[^0-9]/', '', $dateText);
            $date = substr($dateText, 4, 4) . '-' . substr($dateText, 2, 2) . '-' . substr($dateText, 0, 2);
        }

        return $date;
    }

}
