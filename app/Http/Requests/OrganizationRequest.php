<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrganizationRequest extends FormRequest
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
        $id = is_null($this->organization) ? NULL : $this->organization->id; 
        
        $rule = [
            'name' => 'required|unique:organizations,name,' . $id . '|min:3'
        ];     

        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => '組織名稱不可為空',
            'name.unique' => '組織名稱已經存在',
            'name.min' => '組織名稱必須超過三個字元'
        ];
    }
}
