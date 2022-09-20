<?php

namespace App\Http\Requests\EmailTemplate;

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
            'from_email' => 'required',
            'to_email' => 'required',
            'template' => 'required',
            'client_id' => 'required',
            'project_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'from_email.required' => 'Please enter from email',
            'to_email.required' => 'Please enter to email',
            'template.required' => 'Please enter template',
            'client_id.required' => 'Please select client',
            'project_id.required' => 'Please select project',
        ];
    }
}
