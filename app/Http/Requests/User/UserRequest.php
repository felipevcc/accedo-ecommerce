<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$rules = [
			'number_id' => ['required', 'numeric'],
			'name' => ['required', 'string'],
			'last_name' => ['required', 'string'],
			'email' => ['required', 'email'],
			'password' => ['confirmed', 'string', 'min:8'],
		];

		if ($this->method() == 'POST') {
			array_push($rules['number_id'], 'unique:users,number_id');
			array_push($rules['email'], 'unique:users,email');
			array_push($rules['password'], 'required');
		} else {
			dd($this->user->toArray());
			array_push($rules['number_id'], 'unique:users,number_id,' . $this->user->id);
			array_push($rules['email'], 'unique:users,email,' . $this->user->id);
			array_push($rules['password'], 'nullable');
		}

		if ($this->path() != 'api/register') {
			$rules['role'] = ['required', 'string', 'in:user,admin'];
		}

		return $rules;
	}
}
