<?php

namespace App\Http\Requests\CheckListChild;

use App\Http\Requests\BaseRequest;

class StoreCheckListChildRequest extends BaseRequest
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
            'title' => 'required',
            'check_list_id' => 'required | numeric | gt:0',
        ];
    }
}
