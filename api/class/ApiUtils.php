<?php

/**
 * Class ApiUtils
 */
class ApiUtils
{
    const PARAM_ACTION = 'action';

    /**
     * @return string
     */
    public static function getRawBody()
    {
        return file_get_contents('php://input');
    }

    /**
     * @throws ActionNotFoundException
     * @throws InvalidActionException
     * @throws MissingParameterException
     * @throws UnknownActionException
     */
    public static function processRequest()
    {
        $router = new Router();
        $action = $router->segment(1);

        if (!$action) {
            throw new InvalidActionException;
        }

        $action .= self::_getSuffix();

        self::route(
            'Api', $action, Input::get()
        );
    }

    /**
     * @param $class
     * @param $method
     * @param $params
     *
     * @return mixed
     * @throws ActionNotFoundException
     * @throws MissingParameterException
     */
    public static function route($class, $method, $params)
    {
        unset($params[self::PARAM_ACTION]);

        try {
            $reflect = new ReflectionMethod($class, $method);
        } catch (Exception $e) {
            throw new ActionNotFoundException($class, $method);
        }

        $realParams = [];

        foreach ($reflect->getParameters() as $i => $param) {
            $paramName = $param->getName();

            if (array_key_exists($paramName, is_array($params) ? $params : [])) {
                $realParams[] = $params[$paramName];
            } else if ($param->isDefaultValueAvailable()) {
                $realParams[] = $param->getDefaultValue();
            } else {
                throw new MissingParameterException($method, $i + 1, $paramName);
            }
        }

        return call_user_func_array([new $class(), $method], $realParams);
    }

    /**
     * @param array $options
     */
    public static function sendJsonResponse($options)
    {
        if (!Input::get('callback')) {
            header('Cache-Control: no-cache');
            header('Content-Type: application/json');

            echo json_encode($options);
            exit();
        } else {
            ApiUtils::sendPlainResponse(
                Input::get('callback')
                . '(' . json_encode($options) . ');'
            );
        }
    }

    /**
     * @param string $content
     * @param bool   $die
     * @param int    $code
     */
    public static function sendPlainResponse($content, $die = TRUE, $code = 200)
    {
        if($code != 200) {
            http_response_code($code);
        }

        header('Cache-Control: no-cache');
        echo $content;

        if ($die) {
            exit();
        }
    }

    /**
     * @return string
     */
    protected static function _getSuffix()
    {
        return '_' . Input::getRequestMethod();
    }
}