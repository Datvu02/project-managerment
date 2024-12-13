<?php

namespace App\Http\Requests\CheckListChild;

use App\Http\Requests\BaseRequest;
use App\Models\CheckList;
use Illuminate\Validation\Rule;

class ChangeStatusRequest extends BaseRequest
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
            'status' => [Rule::in(CheckList::STATUS)]
        ];
    }
}
