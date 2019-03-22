<?php

namespace App\Helpers;

use App\Article;
use GuzzleHttp\Client;

class ArticleHelper
{
    public static function check($url)
    {
        $source = explode('.', parse_url(urldecode(urldecode($url)))['host']);
        if ($source[0] === "www") {
            $source = $source[1].'.'.$source[2];
        } else {
            $source = $source[0].'.'.$source[1];
        }

        $http = new Client();

        $res = $http->request('GET', 'https://api.diffbot.com/v3/article?token=c0554deda254dc2d9400abf9b2b2674a&url='.$url);
        $data = $res->getBody();


        if (!isset(json_decode($data)->objects)) {
            return [
            'fakenews' => 1,
            'clickbait' => 1,
            'biased' => 1
            ];
        }

        $data = json_decode($data)->objects;

        $content = $data['0']->text;
        $title = $data['0']->title;
        $lang = $data['0']->humanLanguage;

        if (isset($data['0']->publisherCountry)) {
            $country = strtolower($data['0']->publisherCountry);
        } else {
            $country = 'unknown';
        }

        $res = $http->request('GET', 'https://api.fakenewsdetector.org/votes?url='.$url.'&title=article');
        $data = $res->getBody();

        $data = json_decode($data);

        $fakenews = intval(100-intval($data->robot->fake_news*100));

        return [
            'country' => $country,
            'content' => $content,
            'source' => $source,
            'title' => $title,
            'lang' => $lang,
            'fakenews' => $fakenews,
            'clickbait' => intval($data->robot->clickbait*100),
            'biased' => intval($data->robot->extremely_biased*100)
        ];
    }

    public static function getScoreContent($string)
    {
        $score = 0;

        $score += substr_count($string, '!!');
        $score += substr_count($string, '?!!');
        $score += substr_count($string, '?!');
        $score += self::calculateCapslock($string);

        return $score;
    }

    public static function calculateCapslock($string)
    {
        $uppercase = strlen(preg_replace('![^A-Z]+!', '', $string));
        $count = strlen($string);
        return intval($uppercase/$count*100);
    }

    public static function calculateScore($biased, $fakenews, $clickbait, $content, $source)
    {
        $manualScore = self::getScoreContent($content);

        if ($manualScore < 10) {
            $manualScore = 100;
        } else {
            $manualScore = 40;
        }

        $score = ($fakenews - ($manualScore/10) - ($clickbait/10) - ($biased/10));

        $score = intval($score+$source/10);

        if($score > 100){
            $score = 100;
        }

        return $score;
    }
}
