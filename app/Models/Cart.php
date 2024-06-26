<?php

namespace App\Models;

use App\Models\User;
use App\Models\CartDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
	];

	protected $appends = [
		'total_price',
		'product_quantity',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function cartDetails(): HasMany
	{
		return $this->hasMany(CartDetail::class, 'cart_id', 'id');
	}

	public function getTotalPriceAttribute() {
		$total_price = 0;
		foreach ($this->cartDetails as $detail) {
			$detail->load('product');
			if ($detail->product->stock == 0) continue;
			$total_price += $detail->price;
		}
		return $total_price;
	}

	public function getProductQuantityAttribute() {
		$product_quantity = 0;
		foreach ($this->cartDetails as $detail) {
			$detail->load('product');
			if ($detail->product->stock == 0) continue;
			$product_quantity += $detail->amount;
		}
		return $product_quantity;
	}
}
