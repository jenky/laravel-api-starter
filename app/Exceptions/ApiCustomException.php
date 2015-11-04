<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiCustomException extends HttpException
{
    /**
     *  Errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Create a new resource exception instance.
     *
     * @param string                               $message
     * @param array                                $errors
     * @param int                                  $code
     * @param \Exception                           $previous
     * @param array                                $headers
     *
     * @return void
     */
    public function __construct($message = null, $errors = null, $code = 422, Exception $previous = null, $headers = [])
    {
        $this->errors = $errors ?: [];

        parent::__construct($code, $message, $previous, $headers, $code);
    }

    /**
     * Get the errors message bag.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if message bag has any errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }
}
