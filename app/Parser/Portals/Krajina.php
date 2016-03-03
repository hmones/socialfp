<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Krajina extends ContentParser
{

    public function date()
    {
        $date = null;
        $element = $this->crawler->filter('article div header div[class="td-post-date"] time');
        if (count($element) > 0) {
            $dateText = trim($element->attr('datetime'));
            $date = substr($dateText, 0, 10);
        }

        return $date;
    }

    public function isArticle()
    {
        $element = $this->crawler->filter('article');
        if (count($element) > 0) {
            return TRUE;
        }
        return false;
    }

}
