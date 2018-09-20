<?php

/**
 * Class MissingParameterException
 */
class MissingParameterException extends Exception
{
    /**
     * @param string    $method
     * @param string    $number
     * @param string    $name
     * @param Exception $previous
     */
    public function __construct($method = 'Unknown', $number = 'Unknown', $name = 'Unknown', Exception $previous = NULL)
    {
        parent::__construct(
            sprintf('Call to "%s" missing parameter #%s (%s)', $method, $number, $name),
            ErrorCode::MISSING_PARAMETER, $previous
        );
    }
}