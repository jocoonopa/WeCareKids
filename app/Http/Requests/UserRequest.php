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
        $id = is_null($this->user) ? null : $user->id;

        return [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email,' . $id
        ];        
    }

    public function messages()
    {
        return [
            'name.required' => '姓名欄位不可為空',
            'name.min' => '姓名不可少於兩個字元',
            'email.required' => 'E-Mail不可為空',
            'email.email' => 'E-Mail格式不正確',
            'email.unique' => '此E-Mail已被註冊'
        ];
    }
}
