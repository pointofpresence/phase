<?php

/**
 * Class UploadException
 */
class UploadException extends Exception
{
    /**
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct($message = 'Upload error', Exception $previous = NULL)
    {
        parent::__construct($message, ErrorCode::UPLOAD_ERROR, $previous);
    }
}