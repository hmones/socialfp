<?php
namespace App\Parser\Portals;

use App\Parser\ContentParser;

class Vecernji extends ContentParser
{



    public function date()
    {
        $date = null;
        $selectors = [
            'article header aside[class="article_author_detail"] p[class=meta]',
            'header[class="detail_head cf"] div[class="top cf"] aside[class=meta] p'
        ];

        foreach($selectors as $selector){
            $element = $this->crawler->filter($selector);
            if (count($element) > 0) {
                $dateText = $element->text();
                $dateText = preg_replace('/[^0-9\.]/', "", $dateText);
                if (strlen($dateText) == 14) {
                    $date = substr($dateText, 4, 4) . '-' . substr($dateText, 2, 2) . '-' . substr($dateText, 0, 2);
                } elseif (strlen($dateText) == 12) {
                    $date = substr($dateText, 4, 4) . '-' . '0' . substr($dateText, 2, 1) . '-' . '0' . substr($dateText, 0, 1);
                } elseif (preg_match('/\.\d\./', $dateText)) {
                    $date = substr($dateText, 5, 4) . '-' . '0' . substr($dateText, 3, 1) . '-' . substr($dateText, 0, 2);
                } else {
                    $date = substr($dateText, 5, 4) . '-' . substr($dateText, 2, 2) . '-' . '0' . substr($dateText, 0, 1);
                }
                break;
            }
        }
        return $date;
    }

}
