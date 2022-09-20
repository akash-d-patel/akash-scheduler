<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user->id,
            'mobile_no' => 'required|digits:10|unique:users,mobile_no,'.$this->user->id,
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'

        ];
    }

    public function messages()
    {
        return [
            'name' => 'required|min:3|max:50',
            'email' => 'Please enter email',
            'mobile_no.required' => 'Please enter mobile number',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
            
        ];
    }
}