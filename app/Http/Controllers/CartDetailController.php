<?php

namespace App\Http\Controllers;

use App\Models\CartDetail;
use Illuminate\Http\Request;
use App\Http\Requests\Cart\CartDetailRequest;

class CartDetailController extends Controller
{
	public function store(CartDetailRequest $request)
	{
		$cartDetail = new CartDetail($request->all());
		$cartDetail->save();
		if (!$request->ajax()) return back()->with('success', 'Product added to cart');
		return response()->json(['status' => 'Product added to cart', 'cartDetail' => $cartDetail], 201);
	}

	public function update(CartDetailRequest $request, CartDetail $cartDetail)
	{
		$cartDetail->update($request->all());
		if (!$request->ajax()) return back()->with('success', 'Amount updated successfully');
		return response()->json([], 204);
	}

	public function destroy(Request $request, CartDetail $cartDetail)
	{
		$cartDetail->delete();
		if (!$request->ajax()) return back()->with('success', 'Product removed from cart');
		return response()->json([], 204);
	}
}
