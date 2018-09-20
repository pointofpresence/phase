<?php

/**
 * @param $code
 * @param $message
 * @param $file
 * @param $line
 * @param $context
 *
 * @throws ErrorOrWarningException
 */
function errorToException($code, $message, $file, $line, $context)
{
    throw new ErrorOrWarningException($code, $message, $file, $line, $context);
}

/**
 * @param Exception $ex
 */
function globalExceptionHandler(Exception $ex)
{
    http_response_code(400);

    ApiUtils::sendJsonResponse(
        [
            'success'    => FALSE,
            'error_code' => $ex->getCode(),
            'error_line' => $ex->getLine(),
            'error_file' => $ex->getFile(),
            'exception'  => get_class($ex),
            'reason'     => $ex->getMessage(),
        ]
    );
}

set_error_handler('errorToException');
set_exception_handler('globalExceptionHandler');