<?php

namespace App\Http\Requests\SmsTemplate;

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
            'mobile_no' => "required|digits:10|unique:sms_templates,mobile_no,{$this->sms_template->id}",
            'text' => 'required',
            'client_id' => 'required',
            'project_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mobile_no.required' => 'Please enter mobile number',
            'text.required' => 'Please enter text',
            'client_id.required' => 'Please select client',
            'project_id.required' => 'Please select project',
        ];
    }
}
