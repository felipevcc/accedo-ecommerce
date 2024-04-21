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
			$total_price += $detail->price;
		}
		return $total_price;
	}
}
