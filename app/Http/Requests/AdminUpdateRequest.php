<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
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
        $reture = [
            'email' => 'required|unique:admin_users,email,'.$this->get('id').',id|email',
            'phone' => 'required|numeric|regex:/^1[34578][0-9]{9}$/|unique:admin_users,phone,'.$this->get('id').',id',
            'username'  => 'required|min:4|max:14|unique:admin_users,username,'.$this->get('id').',id',
        ];
        if ($this->get('password') || $this->get('password_confirmation')){
            $reture['password'] = 'required|confirmed|min:6|max:14';
        }
        return $reture;
    }
}
