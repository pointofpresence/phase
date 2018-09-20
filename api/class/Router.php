<?php

class Router
{
    private $segments = [];
    private $path;
    private $uri;

    function __construct()
    {
        $this->_getUri();
        $this->_explodeSegments();
    }

    /**
     * @param $str
     *
     * @return mixed
     */
    private static function _filter_uri($str)
    {
        // Convert programatic characters to entities
        $bad  = ['$', '(', ')', '%28', '%29'];
        $good = ['&#36;', '&#40;', '&#41;', '&#40;', '&#41;'];

        return str_replace($bad, $good, $str);
    }

    public function segment($n, $noResult = FALSE)
    {
        return (!isset($this->segments[$n])) ? $noResult : $this->segments[$n];
    }

    /**
     * @return array
     */
    public function getSegments()
    {
        return $this->segments;
    }

    private function _explodeSegments()
    {
        $url = explode('?', $this->uri);
        $url = $url[0];

        $this->path = String::removeFirstLastSymbols("/", $url);
        $segments   = explode('/', $url);

        foreach ($segments as $val) {
            // Filter segments for security
            $val = trim($this->_filter_uri($val));

            if ($val != '') {
                $this->segments[] = $val;
            }
        }
    }

    private function _getUri()
    {
        // Избавляемся от начальных www
        preg_match('~^www\\.~', Input::server('HTTP_HOST'), $matches);

        if ($matches) {
            header(
                'location: http://'
                . str_replace('www.', '', Input::server('HTTP_HOST'))
                . Input::server('REQUEST_URI')
            );
        }

        $requestUri = Input::server('REQUEST_URI');
        $this->uri  = $requestUri;
    }
}