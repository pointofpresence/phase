<?php

/**
 * Class Time
 */
class Time
{
    /**
     * @param string $html
     *
     * @return mixed
     */
    public static function convertTimestamp($html = '')
    {
        $pattern = '|\[ts\](.*?)\[/ts\]|ui';

        return preg_replace_callback(
            $pattern, function ($matches) {
            return self::getTextTimestamp((int)$matches[1]);
        }, $html
        );
    }

    /**
     * @param $time
     *
     * @return string
     */
    public static function getTextTimestamp($time)
    {
        if (!is_int($time)) {
            $time = strtotime($time);
        }

        $timeDifference = time() - $time;
        $seconds        = $timeDifference;
        $minutes        = round($timeDifference / 60);
        $hours          = round($timeDifference / 3600);
        $days           = round($timeDifference / 86400);
        $weeks          = round($timeDifference / 604800);
        $months         = round($timeDifference / 2419200);
        $years          = round($timeDifference / 29030400);

        if ($seconds == 0)
            return "только что";

        if ($seconds <= 60) {
            return $seconds . ' ' . String::getWordForm($seconds, "секунду", "секунды", "секунд") . " назад";
        } elseif ($minutes <= 60) {
            return $minutes . ' ' . String::getWordForm($minutes, "минуту", "минуты", "минут") . " назад";
        } elseif ($hours <= 24) {
            return $hours . ' ' . String::getWordForm($hours, "час", "часа", "часов") . " назад";
        } elseif ($days <= 7) {
            return $days . ' ' . String::getWordForm($days, "день", "дня", "дней") . " назад";
        } elseif ($weeks <= 4) {
            return $weeks . ' ' . String::getWordForm($weeks, "неделю", "недели", "недель") . " назад";
        } elseif ($months <= 12) {
            return $months . ' ' . String::getWordForm($months, "месяц", "месяца", "месяцев") . " назад";
        } else {
            return $years . ' ' . String::getWordForm($years, "год", "года", "лет") . " назад";
        }
    }

}