<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $id = is_null($this->user) ? null : $this->user->id;

        return [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|digits_between:8,12|numeric|unique:users,phone,' . $id, 
        ];        
    }

    public function messages()
    {
        return [
            'name.required' => '姓名栏位不可为空',
            'name.min' => '姓名不可少于两个字元',
            'email.required' => 'E-Mail不可为空',
            'email.email' => 'E-Mail格式不正确',
            'email.unique' => '此E-Mail已被注册',
            'phone.required' => '电话不可为空',
            'phone.digits_between' => '电话号码要介于八~十二个字元',
            'phone.numeric' => '电话号码必须皆为数字',
            'phone.unique' => '此电话号码已被使用',
        ];
    }
}
