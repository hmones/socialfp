<?php
namespace App\Parser;

use Log;
abstract class ContentParser
{
    public $crawler;
    private static $months_trans = array('januar'=>'01','februar'=>'02','mart'=>'03','april'=>'04','maj'=>'05','juni'=>'06','juli'=>'07','august'=>'08','septembar'=>'09','oktobar'=>'10','novembar'=>'11','decembar'=>'12');
    private static $months_trans_CR = array('sijecnja'=>'01','veljace'=>'02','ozujka'=>'03','travnja'=>'04','svibnja'=>'05','lipnja'=>'06','srpnja'=>'07','kolovoza'=>'08','rujna'=>'09','listopada'=>'10','studenog'=>'11','prosinca'=>'12');
    private static $months_trans_EN = array('jan'=>'01','feb'=>'02','mar'=>'03','apr'=>'04','may'=>'05','jun'=>'06','jul'=>'07','aug'=>'08','sep'=>'09','okt'=>'10','nov'=>'11','dec'=>'12');


    public function __construct($crawler)
    {
        $this->crawler = $crawler;
    }

    abstract public function date();
    abstract public function shares();


    public function title()
    {
        return $this->crawler->filter('title')->text();
    }

    public function content()
    {
        return $this->crawler->filter('body')->text();
    }

    public function description()
    {
        $selectors = [
            'meta[property=description]',
            'meta[property="og:description"]',
            'meta[name=description]'
        ];

        foreach ($selectors as $selector){
            $element = $this->crawler->filter($selector);

            if(count($element) > 0){
                return $element->attr('content');
            }
        }

        Log::error('did not found description for current url ');
        return null;

    }

    public function author()
    {
        $element = $this->crawler->filter('meta[name=author]');
        if ($element != null) {
            return $element->attr('content');
        }

        return null;
    }

    public function isArticle()
    {
        $element = $this->crawler->filter('meta[property="og:type"]');
        if ($element != null) {
            return $element->attr('content') == 'article';
        }

        return false;
    }

    public static function month_trans($month, $lang = ''){
        $month = strtolower(trim($month));
        if($lang=='EN'){
            return self::$months_trans_EN[$month];
        }else if($lang =='CR'){
            return self::$months_trans_CR[$month];
        }else{
            return self::$months_trans[$month];
        }

    }
}
