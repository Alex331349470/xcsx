<?php

namespace App\Http\Requests\Api;


class AuthorizationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
            'phone' => 'required',
            'driver_school_id' => 'required'
        ];
    }
}
