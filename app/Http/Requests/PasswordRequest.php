<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'password' => 'required|min:8|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '密碼為必填欄位',
            'password.min' => '密碼必須超過8個字元',
            'password.confirmed' => '密碼兩次輸入不相同'
        ];
    }
}
