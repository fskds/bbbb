<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class NavCreateRequest extends FormRequest
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
            'name'  => 'required|unique:navs|max:200',
        ];
    }
    public function messages()
    {
        $message = [
			'name.required'      =>'标识名称必须填写',
            'name.unique'      =>'标识名称已使用',
			'name.max'      =>'标识名称小于200个字符',
        ];
        return $message;
    }
	
	protected function failedValidation(Validator $validator) {
		throw (new HttpResponseException(response()->json([
			'code' => 2,
			'msg' => $validator->errors()->first(),
		], 200)));
    }
}
