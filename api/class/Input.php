<?php

/**
 * Class Input
 */
class Input
{
    private static $_rawBody;

    /**
     * @param string $index
     * @param bool   $default
     *
     * @return array|bool|mixed
     */
    public static function files($index = NULL, $default = FALSE)
    {
        // Check if a field has been provided
        if ($index === NULL && !empty($_FILES)) {
            $files = [];

            // Loop through the full _FILES array and return it
            foreach (array_keys($_FILES) as $key) {
                $files[$key] = self::_fetchFromArray($_FILES, $key);
            }

            return $files;
        }

        $result = self::_fetchFromArray($_FILES, $index);

        if ($result === FALSE) {
            $result = $default;
        }

        return $result;
    }

    /**
     * Fetch an item from the GET array
     *
     * @access   public
     *
     * @param      string
     * @param bool $default
     *
     * @return   string
     */
    public static function get($index = NULL, $default = FALSE)
    {
        // Check if a field has been provided
        if ($index === NULL && !empty($_GET)) {
            $get = [];

            // loop through the full _GET array
            foreach (array_keys($_GET) as $key) {
                $get[$key] = self::_fetchFromArray($_GET, $key);
            }

            return $get;
        }

        $result = self::_fetchFromArray($_GET, $index);

        if ($result === FALSE) {
            $result = $default;
        }

        return $result;
    }

    /**
     * Fetch an item from either the GET array or the POST
     *
     * @access   public
     *
     * @param    string $index The index key
     * @param bool      $default
     *
     * @return string
     */
    public static function getPost($index = '', $default = FALSE)
    {
        if (!isset($_POST[$index])) {
            return self::get($index, $default);
        } else {
            return self::post($index, $default);
        }
    }

    /**
     * @param bool $decode
     *
     * @return mixed|string
     */
    public static function getRawBody($decode = FALSE)
    {
        if (self::$_rawBody === NULL) {
            self::$_rawBody = file_get_contents('php://input');
        }

        return $decode ? json_decode(self::$_rawBody) : self::$_rawBody;
    }

    /**
     * @return string
     */
    public static function getRequestMethod()
    {
        return strtolower(self::server('REQUEST_METHOD'));
    }

    /**
     * @return bool
     */
    public static function isAjaxRequest()
    {
        return (self::server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
    }

    /**
     * Is cli Request?     *
     * Test to see if a request was made from the command line
     *
     * @return    bool
     */
    public static function isCliRequest()
    {
        return (php_sapi_name() === 'cli' || defined('STDIN'));
    }

    /**
     * @return bool
     */
    public static function isPostRequest()
    {
        return (self::getRequestMethod() == 'post');
    }

    /**
     * Parse cli arguments     *
     * Take each command line argument and assume it is a URI segment.
     *
     * @return    string
     */
    public static function parseCliArgs()
    {
        $args = array_slice(self::server('argv'), 1);

        return $args ? '/' . implode('/', $args) : '';
    }

    /**
     * Fetch an item from the POST array
     *
     * @access   public
     *
     * @param string $index
     * @param bool   $default
     *
     * @return   string
     */
    public static function post($index = NULL, $default = FALSE)
    {
        // Check if a field has been provided
        if ($index === NULL && !empty($_POST)) {
            $post = [];

            // Loop through the full _POST array and return it
            foreach (array_keys($_POST) as $key) {
                $post[$key] = self::_fetchFromArray($_POST, $key);
            }

            return $post;
        }

        $result = self::_fetchFromArray($_POST, $index);

        if ($result === FALSE) {
            $result = $default;
        }

        return $result;
    }

    /**
     * редирект на страницу сайта. путь указывать относительно сайта
     * $header - 301 или 302 редирект
     *
     * @param string   $url
     * @param bool     $refresh
     * @param bool|int $header
     */
    public static function redirect($url = NULL, $refresh = FALSE, $header = 301)
    {
        if (!$url) {
            $url = self::server('HTTP_REFERER') ? self::server('HTTP_REFERER') : "/";
        }

        $url = strip_tags($url);
        $url = str_replace(['%0d', '%0a'], '', $url);

        if ($header == 301)
            header('HTTP/1.1 301 Moved Permanently');
        elseif ($header == 302)
            header('HTTP/1.1 302 Found');

        if ($refresh) {
            header("Refresh: 0; url={$url}");
        } else {
            header("Location: {$url}");
        }

        exit();
    }

    /**
     * @param bool $relative
     *
     * @return string
     */
    public static function selfUrl($relative = TRUE)
    {
        $sUri = self::server('REQUEST_URI');

        if (!$sUri) {
            $sUri = self::server('PHP_SELF');
        }

        $sUri = dirname($sUri);

        if (substr($sUri, -1) != '/') {
            $sUri .= '/';
        }

        if ($relative) {
            return $sUri;
        }

        $s        = self::server('HTTPS') == 'on' ? 's' : '';
        $protocol = strtolower(self::server('SERVER_PROTOCOL'));
        $protocol = substr($protocol, 0, strpos($protocol, '/')) . $s;
        $port     = self::server('SERVER_PORT') == '80' ? '' : (':' . self::server('SERVER_PORT'));

        return $protocol . '://' . self::server('SERVER_NAME') . $port . $sUri;
    }

    /**
     * @param string $index
     *
     * @return bool|mixed
     */
    public static function server($index = '')
    {
        return self::_fetchFromArray($_SERVER, $index);
    }

    /**
     * @param        $array
     * @param string $index
     *
     * @return bool|mixed
     */
    private static function _fetchFromArray(&$array, $index = '')
    {
        if (!isset($array[$index])) {
            return FALSE;
        }

        return $array[$index];
    }
}