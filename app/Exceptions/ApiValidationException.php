<?php

namespace App\Exceptions;

use Dingo\Api\Exception\ValidationHttpException;

class ApiValidationException extends ValidationHttpException
{
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
