<?php

class DBConnection
{
    protected static $instance;

    /**
     * @param CSqlite3 $instance
     *
     * @return CSqlite3
     */
    public static function init($instance)
    {
        self::$instance = $instance;

        return $instance;
    }

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DBConnection;
        }

        return self::$instance;
    }
}