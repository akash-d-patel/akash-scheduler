<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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

            'name' => 'required|min:3|max:50|alpha',
            'email' => 'required|email|unique:users,email',
            'mobile_no' => 'required|digits:10|numeric|unique:users',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',               
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter name',
            // 'email.required' => 'Please enter email',
            'mobile_no.required' => 'Please enter mobile number',     
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'    
        ];
    }
}