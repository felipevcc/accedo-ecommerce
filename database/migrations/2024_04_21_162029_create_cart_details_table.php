<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_details', function (Blueprint $table) {
			$table->id();
			$table->integer('amount');
			$table->bigInteger('product_id')->unsigned();
			$table->bigInteger('cart_id')->unsigned();
			$table->timestamps();

			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('cart_details');
	}
};
