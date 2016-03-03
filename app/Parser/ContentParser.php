<?php
namespace App\Parser;

use Log;

abstract class ContentParser
{
    public $crawler;
    public $url;
    private static $months_trans = array('januar' => '01', 'februar' => '02', 'mart' => '03', 'april' => '04', 'maj' => '05', 'juni' => '06', 'juli' => '07', 'august' => '08', 'septembar' => '09', 'oktobar' => '10', 'novembar' => '11', 'decembar' => '12');
    private static $months_trans_CR = array('sijecnja' => '01', 'veljace' => '02', 'ozujka' => '03', 'travnja' => '04', 'svibnja' => '05', 'lipnja' => '06', 'srpnja' => '07', 'kolovoza' => '08', 'rujna' => '09', 'listopada' => '10', 'studenog' => '11', 'prosinca' => '12');
    private static $months_trans_EN = array('jan' => '01', 'feb' => '02', 'mar' => '03', 'apr' => '04', 'may' => '05', 'jun' => '06', 'jul' => '07', 'aug' => '08', 'sep' => '09', 'okt' => '10', 'nov' => '11', 'dec' => '12');

    public function __construct($crawler, $url)
    {
        $this->crawler = $crawler;
        $this->url = $url;
    }

    abstract public function date();

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
            'meta[name=description]',
            'meta[property="og:title"]',
        ];

        foreach ($selectors as $selector) {
            $element = $this->crawler->filter($selector);

            if (count($element) > 0) {
                $content = $element->attr('content');
                if ($content == '') {
                    continue;
                }

                return $content;
            }
        }

        Log::error('did not found description for current url ');
        return $this->title();

    }

    public function author()
    {
        $element = $this->crawler->filter('meta[name=author]');
        if (count($element) > 0) {
            return $element->attr('content');
        }

        return null;
    }

    public function isArticle()
    {
        $element = $this->crawler->filter('meta[property="og:type"]');
        if (count($element) > 0) {
            $r =  trim($element->attr('content')) == 'article';
            return $r;
        }
        
        return $this->date() != null;
    }

    public static function month_trans($month, $lang = '')
    {
        $month = strtolower(trim($month));
        if ($lang == 'EN') {
            return self::$months_trans_EN[$month];
        } else if ($lang == 'CR') {
            return self::$months_trans_CR[$month];
        } else {
            return self::$months_trans[$month];
        }
    }

    public function social()
    {
        $url = $this->url;
        $SMresults = array('gp_shares' => 0, 'fb_likes' => 0, 'fb_shares' => 0, 'fb_comments' => 0);

        $context = stream_context_create(array('http' => array('ignore_errors' => true)));
        $json_string = @file_get_contents(sprintf('https://api.facebook.com/method/links.getStats?urls=%s&format=json', $url), false, $context);
        if ($json_string == false || $json_string == null) {
            Log::error('Facebook API failed for this URL: ' . $url);
        } else {
            $json = json_decode($json_string, true);

            if (!empty($json[0]['share_count'])) {
                $SMresults['fb_shares'] = $json[0]['share_count'];
            }

            if (!empty($json[0]['like_count'])) {
                $SMresults['fb_likes'] = $json[0]['like_count'];
            }

            if (!empty($json[0]['comment_count'])) {
                $SMresults['fb_comments'] = $json[0]['comment_count'];
            }

        }
        //print('fbout');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        $curl_results = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($curl_results, true);
        if (!empty($json[0]['result']['metadata']['globalCounts']['count'])) {
            $SMresults['gp_shares'] = $json[0]['result']['metadata']['globalCounts']['count'];
        }

        return $SMresults;
    }
}
