<?php

namespace App\Exceptions;

use Dingo\Api\Exception\ResourceException;

class ApiValidationException extends ResourceException
{
    /**
     * Create a new validation HTTP exception instance.
     *
     * @param \Illuminate\Support\MessageBag|array $errors
     * @param string                               $message
     * @param \Exception                           $previous
     * @param array                                $headers
     * @param int                                  $code
     *
     * @return void
     */
    public function __construct($errors = null, $message = null, Exception $previous = null, $headers = [], $code = 0)
    {
        parent::__construct($message, $errors, $previous, $headers, $code);
    }

    /**
     * Get the errors message.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->formatErrors($this->errors);
    }

    /**
     * Format the validation errors.
     *
     * @param \Illuminate\Support\MessageBag $errors
     *
     * @return mixed
     */
    protected function formatErrors($errors)
    {
        $output = [];

        foreach ($errors->getMessages() as $field => $message) {
            $output[] = [
                'code'    => 'validation_fails',
                'field'   => $field,
                'message' => isset($message[0]) ? $message[0] : '',
            ];
        }

        return $output;
    }
}
