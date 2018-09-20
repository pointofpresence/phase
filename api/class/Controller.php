<?php

/**
 * Class Controller
 */
class Controller
{
    public function __construct()
    {
        Session::safeSet('implicit', FALSE);
    }

    /**
     * @param      $name
     * @param      $options
     * @param bool $die
     *
     */
    protected function getTemplate(
        $name,
        /** @noinspection PhpUnusedParameterInspection */
        $options = [],
        $die = TRUE
    ) {
        if (!Session::safeGet('implicit')) {

            while (ob_get_level()) {
                @ob_end_flush();
            }

            ob_implicit_flush(TRUE);
            Session::safeSet('implicit', TRUE);
        }

        /** @noinspection PhpIncludeInspection */
        include TEMPLATE_PATH . DS . $name . '.php';

        if ($die) {
            exit();
        }
    }
}