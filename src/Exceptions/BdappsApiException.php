<?php

namespace Kazinokib\BdappsApi\Exceptions;

use Exception;

class BdappsApiException extends Exception
{
    protected $errorCode;
    protected $errorDetail;

    public function __construct($message = "", $code = 0, $errorDetail = "", Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorCode = $code;
        $this->errorDetail = $errorDetail;
    }

    /**
     * Get the error code from the API response.
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Get the detailed error message from the API response.
     *
     * @return string
     */
    public function getErrorDetail()
    {
        return $this->errorDetail;
    }

    /**
     * Convert the exception to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n"
            . "Error Code: {$this->errorCode}\n"
            . "Error Detail: {$this->errorDetail}";
    }
}
