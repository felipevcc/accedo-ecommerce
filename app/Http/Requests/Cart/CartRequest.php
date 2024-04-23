<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'user_id' => ['required', 'exists:users,id'],
		];
	}

	public function messages()
	{
		return [
			'user_id.required' => 'El usuario es requerido.',
			'user_id.exists' => 'El usuario no existe.',
		];
	}
}
