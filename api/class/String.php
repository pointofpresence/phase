<?php

class String
{
    /**
     * Получить нормальное окончание для слова $word и числа объектов $n.
     * @param int $n
     * @param string $o1 окончание для числа *1,
     * @param string $o2 окончание для *2-*4,
     * @param string $o5 окончание для чисел *5-*9,*0,*11-*19*
     * @return mixed
     */
    public static function getWordForm($n, $o1, $o2, $o5)
    {
        $n = abs($n);

        if ($n % 100 > 10 && $n % 100 < 20 || $n % 10 == 0 || $n % 10 > 4) {
            $word = $o5;
        } elseif ($n % 10 > 1 && $n % 10 < 5) {
            $word = $o2;
        } else {
            $word = $o1;
        }

        return $word;
    }

    /**
     * удаляем крайние сивмолы слева и справа
     *
     * @param mixed $ch - какой символ удалять
     * @param mixed $s  - где
     *
     * @return string
     */
    public static function removeFirstLastSymbols($ch, $s)
    {
        $ret = self::removeFirstSymbol($ch, $s);
        $ret = self::removeLastSymbol($ch, $ret);
        return $ret;
    }

    /**
     * удаляем первый символ
     *
     * @param mixed $ch - какой символ удалять
     * @param mixed $s - где
     * @return string
     */
    public static function removeFirstSymbol($ch, $s)
    {
        // remove the first character (if any). Example: '/dir/' => 'dir/'
        if (strlen($s) == 0)
            return $s;

        if ($s[0] == $ch)
            return substr($s, 1, strlen($s));

        return $s;
    }

    /**
     * удаляем последний символ
     *
     * @param mixed $ch - какой символ удалять
     * @param mixed $s - где
     * @return string
     */
    public static function removeLastSymbol($ch, $s)
    {
        // remove the last character (if any). Example: '/dir/' => '/dir'
        $len = strlen($s);

        if ($len == 0)
            return $s;

        if ($s[$len - 1] == $ch)
            return substr($s, 0, $len - 1);

        return $s;
    }

    /**
     * @param $str
     *
     * @return string
     */
    public static function stripBadUTF8($str)
    {
        // based on ru.wikipedia.org/wiki/Unicode
        $ret = '';

        for ($i = 0; $i < strlen($str);) {
            $tmp = $str{$i++};
            $ch  = ord($tmp);

            if ($ch > 0x7F) {
                if ($ch < 0xC0)
                    continue;
                elseif ($ch < 0xE0)
                    $di = 1;
                elseif ($ch < 0xF0)
                    $di = 2;
                elseif ($ch < 0xF8)
                    $di = 3;
                elseif ($ch < 0xFC)
                    $di = 4;
                elseif ($ch < 0xFE)
                    $di = 5;
                else
                    continue;

                for ($j = 0; $j < $di; $j++) {
                    $tmp .= $ch = @$str{$i + $j};
                    $ch = ord($ch);

                    if ($ch < 0x80 || $ch > 0xBF)
                        continue 2;
                }

                $i += $di;
            }

            $ret .= $tmp;
        }

        return $ret;
    }

    /**
     * UTF-8 only!
     *
     * @param $html
     * @param $xpathString array|string
     * @return string
     */
    public static function removeDomNodes($html, $xpathString)
    {
        // Ignore errors
        libxml_use_internal_errors(TRUE) AND libxml_clear_errors();

        $dom = new DOMDocument('1.0', 'UTF-8');

        $dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        $xpath = new DOMXPath($dom);

        if (!is_array($xpathString)) {
            $xpathString = [$xpathString];
        }

        foreach ($xpathString as $xString) {
            while ($node = $xpath->query($xString)->item(0)) {
                $node->parentNode->removeChild($node);
            }
        }

        return $dom->saveHTML($dom->documentElement);
    }
}