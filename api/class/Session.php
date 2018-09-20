<?php

/**
 * Class Session
 */
class Session
{
    const SAFE = 'ss_safe';

    /**
     * @return string
     */
    static function debug()
    {
        return !empty($_SESSION[self::SAFE]) ? $_SESSION[self::SAFE] : NULL;
    }

    /**
     * @return string
     */
    static function getId()
    {
        $id = session_id();

        return $id;
    }

    /**
     * @return void
     */
    static function safeClear()
    {
        if (!empty($_SESSION[self::SAFE])) {
            unset($_SESSION[self::SAFE]);
        }
    }

    /**
     * @param      $key
     * @param bool $unset
     *
     * @return null
     */
    static function safeGet($key, $unset = FALSE)
    {
        $data = !empty($_SESSION[self::SAFE][$key]) ? $_SESSION[self::SAFE][$key] : NULL;

        if ($unset) {
            unset($_SESSION[self::SAFE][$key]);
        }

        return $data;
    }

    /**
     * @param $key
     * @param $data
     */
    static function safeSet($key, $data)
    {
        $_SESSION[self::SAFE][$key] = $data;
    }

    /**
     * @param $key
     */
    static function safeUnset($key)
    {
        if (isset($_SESSION[self::SAFE][$key])) {
            unset($_SESSION[self::SAFE][$key]);
        }
    }
}