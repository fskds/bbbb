<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadRequest extends FormRequest
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
			'name'  => 'required|min:2|max:14|unique:admin_users',
            'email' => 'required|unique:admin_users|email',
            'phone'   => 'required|numeric|regex:/^1[3456789][0-9]{9}$/|unique:admin_users',
            'username'  => 'required|min:4|max:14|unique:admin_users',
            'password'  => 'required|confirmed|min:6|max:14'
        ];
    }
	public function messages()
    {
        $message = [
			'name.required'      =>'昵称必须填写',
            'name.unique'      =>'昵称已使用',
			'name.min'      =>'昵称大于2个字符',
			'name.max'      =>'昵称小于14个字符',
            'email.required'      =>'邮箱必须填写',
            'email.unique'      =>'邮箱已使用',
			'email.email'      =>'邮箱格式错误',
			'phone.required'      =>'电话号码必须填写',
            'phone.unique'      =>'电话号码已使用',
			'phone.regex'      =>'电话号码格式错误',
			'username.required'      =>'用户名必须填写',
            'username.unique'      =>'用户名已使用',
			'username.min'      =>'用户名大于4个字符',
			'username.man'      =>'用户名小于14个字符',
			'password.required'      =>'密码必须填写',
            'password.confirmed'      =>'密码不一致',
			'password.min'      =>'密码大于4个字符',
			'password.man'      =>'密码小于14个字符',

        ];
        return $message;
    }
	
	protected function failedValidation(Validator $validator) {
		throw (new HttpResponseException(response()->json([
			'code' => 500,
			'msg' => $validator->errors()->first(),
		], 200)));
    }
}
