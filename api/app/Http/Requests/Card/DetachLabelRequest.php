<?php

namespace App\Http\Requests\Card;

use App\Http\Requests\BaseRequest;

class DetachLabelRequest extends BaseRequest
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
            'label_id' => 'required | numeric | gt:0',
        ];
    }
}
