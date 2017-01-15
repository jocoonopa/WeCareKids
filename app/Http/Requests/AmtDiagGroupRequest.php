<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmtDiagGroupRequest extends FormRequest
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
            'content' => 'required|string|min: 5'
        ];
    }

    public function messages()
    {
        return [
            'content.required'  => '標題描述不可為空',
            'content.min' => '標題描述至少要超過五個字'
        ];
    }
}
