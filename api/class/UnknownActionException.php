<?php

/**
 * Class UnknownActionException
 */
class UnknownActionException extends Exception
{
    /**
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct($message = 'Action not found', Exception $previous = NULL)
    {
        parent::__construct($message, ErrorCode::UNKNOWN_ACTION, $previous);
    }
}