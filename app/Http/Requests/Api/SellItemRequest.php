<?php

namespace App\Http\Requests\Api;


class SellItemRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'time' => 'required',
            'price' => 'required'
        ];
    }
}
