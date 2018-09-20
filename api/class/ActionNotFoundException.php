<?php

/**
 * Class ActionNotFoundException
 */
class ActionNotFoundException extends Exception
{
    /**
     * @param string    $class
     * @param string    $method
     * @param Exception $previous
     */
    public function __construct($class = 'Unknown', $method = 'Unknown', Exception $previous = NULL)
    {
        parent::__construct(
            sprintf('Action "%s" not found in "%s"', $method, $class),
            ErrorCode::ACTION_NOT_FOUND, $previous
        );
    }
}