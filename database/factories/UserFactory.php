<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'number_id' => $this->faker->randomNumber(9, true),
			'name' => $this->faker->name(),
			'last_name' => $this->faker->name(),
			'email' => $this->faker->unique()->safeEmail(),
			'email_verified_at' => now(),
			'password' => '123456789', // password
			'remember_token' => Str::random(30),
		];
	}
}
