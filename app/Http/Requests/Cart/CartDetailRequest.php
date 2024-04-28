<?php

namespace App\Http\Requests\Cart;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CartDetailRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'amount' => ['required', 'numeric', 'min:1'],
			'product_id' => ['required', 'exists:products,id'],
			'cart_id' => ['required', 'exists:carts,id'],
		];
	}

	public function messages()
	{
		return [
			'amount.required' => 'La cantidad es requerida.',
			'amount.numeric' => 'La cantidad debe ser un numero valido.',
			'amount.min' => 'La cantidad minima es 1.',
			'product_id.required' => 'El producto es requerido.',
			'product_id.exists' => 'El producto no existe.',
			'cart_id.required' => 'El carrito es requerido.',
			'cart_id.exists' => 'El carrito no existe.',
		];
	}
}
