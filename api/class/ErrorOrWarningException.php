<?php

/**
 * Class ErrorOrWarningException
 */
class ErrorOrWarningException extends Exception
{
    /**
     * @var null
     */
    protected $_context = NULL;

    /**
     * @param int|string $code
     * @param string     $message
     * @param string     $file
     * @param string     $line
     * @param string     $context
     */
    public function __construct($code = ErrorCode::UNKNOWN, $message = 'Error', $file = '', $line = '', $context = '')
    {
        parent::__construct($message, $code);

        $this->file = $file;
        $this->line = $line;
        $this->setContext($context);
    }

    /**
     * @return null|string
     */
    public function getContext()
    {
        return $this->_context;
    }

    /**
     * @param $value
     */
    public function setContext($value)
    {
        $this->_context = $value;
    }
}