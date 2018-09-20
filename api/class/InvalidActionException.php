<?php

/**
 * Class InvalidActionException
 */
class InvalidActionException extends Exception
{
    /**
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct($message = 'Action param is empty', Exception $previous = NULL)
    {
        parent::__construct($message, ErrorCode::INVALID_ACTION, $previous);
    }
}