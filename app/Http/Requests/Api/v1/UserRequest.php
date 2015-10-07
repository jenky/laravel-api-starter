<?php

namespace App\Http\Requests\API\v1;

use App\Http\Requests\ApiRequest;
use App\Models\User;

class UserRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->route('users') ? get_update_rules(User::$rules) : User::$rules;
    }
}
