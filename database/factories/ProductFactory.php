<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
	public function definition()
	{
		return [
			'category_id' => $this->faker->randomElement([1, 2, 3, 4]),
			'name' => $this->faker->sentence(5),
			'description' => $this->faker->text(200),
			'price' => $this->faker->numberBetween(1000, 500000),
			'stock' => $this->faker->numberBetween(0, 50),
		];
	}

	public function configure()
	{
		return $this->afterCreating(function (Product $product) {
			$image = new Image(['route' => '/storage/images/products/default.png']);
			$product->image()->save($image);
		});
	}
}
