<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
	protected $rules = [
		'category_id' => ['required', 'exists:categories,id'],
		'name' => ['required', 'string'],
		'description' => ['required', 'string'],
		'price' => ['required', 'numeric'],
		'stock' => ['required', 'numeric'],
		'image' => ['required', 'image'],
	];

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return $this->rules;
	}

	public function messages()
	{
		return [
			'name.required' => 'El nombre es requerido.',
			'name.string' => 'El nombre debe ser valido.',
			'description.required' => 'La descripción es requerida.',
			'description.string' => 'La descripción debe ser valida.',
			'price.required' => 'El precio es requerido.',
			'price.numeric' => 'El precio debe ser valido.',
			'stock.required' => 'La cantidad es requerida.',
			'stock.numeric' => 'La cantidad debe ser un numero valido.',
			'category_id.required' => 'La categoría es requerida.',
			'category_id.exists' => 'La categoría no existe.',
			'image.required' => 'La imagen es requerida.',
			'image.image' => 'El archivo debe ser una imagen valida.',
		];
	}
}
