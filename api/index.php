<?php

/**
 * @param $className
 */
function __autoload($className)
{
    /** @noinspection PhpIncludeInspection */
    include CLASS_PATH . DS . $className . '.php';
}

require_once 'config.php';
require_once 'exception.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_PATH);

setlocale(LC_ALL, "ru_RU.UTF-8", 'rus');
header('Content-Type: text/html; charset=UTF-8');

mb_http_input('UTF-8');
mb_http_output('UTF-8');
mb_internal_encoding("UTF-8");

header('X-Accel-Buffering: no');

ini_set('output_buffering', 'Off');
ini_set('output_handler', '');
ini_set('zlib.output_handler', '');
ini_set('zlib.output_compression', 'Off');

session_start();
ApiUtils::processRequest();