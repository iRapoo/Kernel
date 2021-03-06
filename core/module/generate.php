<?php

class Generate
{

    /*
     * method @genRandomString
     *
     * $chars - set symbols or false
     * $max - set maximum length after prefix
     * */

    function genRandomString($chars = false,
                             $max = false, $qx = true){
        $chars=(!$chars)?"1234567890qazxswedcvfrtgbnhyujmkiolp":$chars;
        $max=(!$max)?10:$max;
        $size=StrLen($chars)-1;
        $name=null;

        while($max--)
            $name.= $chars[rand(0,$size)];

        return ($qx) ? "qx_".$name : $name;
    }

    function setHideKey($string){
        $string = self::convertIntToString($string);
        $c_string = strlen($string);
        $g_string = self::genRandomString("AbcDEfGopZ", 11, false);

        for($i = 0; $i < $c_string; $i++)
            $g_string[$i*2] = $string[$i];

        if($c_string>6)
            $g_string = $string;

        return $g_string;
    }

    function getHideKey($string){
        $string = self::convertStringToInt($string);

        return preg_replace('/[^0-9]/', '', $string);
    }

    function convertIntToString($int){
        $converter = array(
            '1' => 'a', '2' => 'B', '3' => 'C',
            '4' => 'd', '5' => 'e', '6' => 'F',
            '7' => 'g', '8' => 'O', '9' => 'P',
            '0' => 'z'
        );

        return strtr($int, $converter);
    }

    function convertStringToInt($string){
        $converter = array(
            'a' => '1', 'B' => '2', 'C' => '3',
            'd' => '4', 'e' => '5', 'F' => '6',
            'g' => '7', 'O' => '8', 'P' => '9',
            'z' => '0'
        );

        return strtr($string, $converter);
    }

    /*
     * method @rus2translit
     *
     * $string - set string ot transliterate
     * */

    function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => 'i',  'ы' => 'y',   'ъ' => 'i',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            ' ' => '_', '—' => '-', '–' => '-',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => 'i',  'Ы' => 'Y',   'Ъ' => 'i',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

            //Укр алфавит
            'і' => 'i', 'ґ' => 'g', 'ї' => 'y', 'є' => 'e',
            'І' => 'I', 'Ґ' => 'G', 'Ї' => 'Y', 'Є' => 'E',
        );
        return strtr($string, $converter);
    }

    /*
     * method @str2url
     *
     * $str - set string to convert
     * */

    function str2url($str) {
        // переводим в транслит
        $str = $this->rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }

    function YATranslate ($string, $lang1, $lang2, $enabled){
        $str = str_replace(' ', '+', $string);

        $lang = ($lang2) ? 'lang='.$lang1.'-'.$lang2.'&' : 'lang='.$lang1.'&';

        $url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?' .
            'key=trnsl.1.1.20170604T110559Z.0af74b8a7abfab2e.6a5c71a11fde0919fec1274f72200ce41372be05&' .
            'text='.$str.'&' .
            'lang='.$lang1.'-'.$lang2.'&' .
            'format=plain&' .
            'options=1';

        $curlObject = curl_init();

        curl_setopt($curlObject, CURLOPT_URL, $url);

        curl_setopt($curlObject, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlObject, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($curlObject);

        curl_close($curlObject);

        if ($responseData === false) {
            throw new Exception('Response false');
        }

        if ($responseData AND $enabled) {
            $array = json_decode($responseData, true);
            $text = $array['text'][0];
        } else {
            $text = $string;
        }
        return $text;
    }

}