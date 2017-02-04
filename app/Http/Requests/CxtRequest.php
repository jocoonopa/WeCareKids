<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CxtRequest extends FormRequest
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
            'child_name' => 'required|min:2',
            'child_birthday' => 'required|date',
            'filler_name'=> 'required|min:2',
            'phone'=> 'required|digits_between:10,13|numeric',
            'email' => 'email',
        ];
    }

    public function messages()
    {
        return [
            'child_name.required' => '孩童姓名为必填栏位',
            'child_name.min' => '孩童姓名不可少于两个字元',
            'child_birthday.required' => '孩童生日为必填栏位',
            'child_birthday.date' => '孩童生日格式不正确',
            'filler_name.required' => '填写人姓名为必填栏位',
            'filler_name.min' => '填写人姓名不可少于两个字元',
            'phone.required' => '联络电话为必填栏位',
            'phone.digits_between' => '联络电话长度必须为10至13个字元',
            'phone.numeric' => '联络电话仅可使用数字',
            'email.email' => '信箱格式不正确',
        ];
    }
}
