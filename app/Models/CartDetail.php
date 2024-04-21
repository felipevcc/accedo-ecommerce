<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartDetail extends Model
{
	use HasFactory;

	protected $fillable = [
		'amount',
		'product_id',
		'cart_id',
	];

	protected $appends = [
		'price',
	];

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class, 'product_id', 'id');
	}

	public function cart(): BelongsTo
	{
		return $this->belongsTo(Cart::class, 'cart_id', 'id');
	}

	public function getPriceAttribute()
	{
		return $this->product->price * $this->amount;
	}
}
