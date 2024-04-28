<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
	public function run()
	{
		Category::create(['name' => 'Vehículos']);
		Category::create(['name' => 'Tecnología']);
		Category::create(['name' => 'Deportes y Fitness']);
		Category::create(['name' => 'Hogar y Muebles']);
		Category::create(['name' => 'Belleza y Cuidado Personal']);
		Category::create(['name' => 'Construcción']);
	}
}
