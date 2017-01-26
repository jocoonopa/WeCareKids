<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WckUsageRecordRequest extends FormRequest
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
            'variety' => 'required|numeric',
            'brief' => 'required|min:4',
        ];
    }

    public function messages()
    {
        return [
            'variety.required' => '金额变量为必填栏位',
            'variety.numeric' => '金额变量必须为数字',
            'brief.required' => '交易纪录描述为必填栏位',
            'brief.min' => '交易纪录描述最少需要4个字元',
        ];
    }
}
