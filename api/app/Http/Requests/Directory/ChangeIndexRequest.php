<?php

namespace App\Http\Requests\Directory;

use App\Http\Requests\BaseRequest;

class ChangeIndexRequest extends BaseRequest
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
        return [
            'index' => 'required | numeric',
        ];
    }
}
