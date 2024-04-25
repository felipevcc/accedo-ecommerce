<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Category;
use App\Models\CartDetail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'category_id',
		'name',
		'description',
		'price',
		'stock',
	];

	protected $appends = [
		'format_name',
	];

	public function formatName(): Attribute
	{
		return Attribute::make(
			get: function ($value, $attributes) {
				return Str::limit($attributes['name'], 40, '...');
			},
		);
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}

	public function image()
	{
		return $this->morphOne(Image::class, 'imageable');
	}

	public function cartDetails(): HasMany
	{
		return $this->hasMany(CartDetail::class, 'product_id', 'id');
	}

	public function scopeAvailable($query)
	{
		return $query->where('stock', '>', 0);
	}

	public function scopeSearch($query, $searchTerm)
	{
		return $query->where('name', 'LIKE', '%' . $searchTerm . '%');
	}
}
