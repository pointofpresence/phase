<?php

/**
 * @param $const
 * @param $value
 */
function redefine($const, $value)
{
    if (!defined($const)) {
        define($const, $value);
    }
}

define('DS', DIRECTORY_SEPARATOR);

$local = __DIR__ . DS . 'cur_env.php';

if (is_file($local)) {
    /** @noinspection PhpIncludeInspection */
    require_once $local;
}

define('CLASS_PATH', __DIR__ . DS . 'class');
define('DB_PATH', __DIR__ . DS . '..' . DS . 'db' . DS . 'base.s3db');

define('UPLOAD_DIR', 'uploads');
define('UPLOAD_PATH', __DIR__ . DS . '..' . DS . UPLOAD_DIR);

