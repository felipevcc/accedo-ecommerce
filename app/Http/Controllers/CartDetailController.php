<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Cart\CartDetailRequest;
use App\Models\Product;

class CartDetailController extends Controller
{
	public function store(Request $request)
	{
		try {
			DB::beginTransaction();
			/** @var \App\Models\User\User $user */
			$user = Auth::user();
			$product = Product::find($request->product_id);

			// Get the user's cart and if it doesn't exist, create it
			$cart = $user->cart;
			if (!$cart) {
				$cart = new Cart();
				$cart->user_id = $user->id;
				$cart->save();
			}

			// Validate if the product was already in the cart
			$cartDetail = $cart->cartDetails()->where('product_id', $product->id)->first();

			if ($cartDetail) {
				// If it was in the cart, add 1 to the quantity
				$cartDetail->amount += 1;
			} else {
				// If it was not in the cart add it
				$cartDetail = new CartDetail();
				$cartDetail->product_id = $product->id;
				$cartDetail->cart_id = $cart->id;
				$cartDetail->amount = 1;
			}

			// Check stock
			if ($cartDetail->amount > $product->stock) {
				DB::rollback();
				if (!$request->ajax()) return back()->with('error', 'Product out of stock');
				return response()->json(['error' => 'Product out of stock'], 400);
			}

			$cartDetail->save();
			DB::commit();
			if (!$request->ajax()) return back()->with('success', 'Product added to cart');
			return response()->json(['status' => 'Product added to cart', 'cartDetail' => $cartDetail], 201);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}

	public function update(CartDetailRequest $request, CartDetail $cartDetail)
	{
		// Check stock
		if ($request->amount > $cartDetail->product->stock) {
			if (!$request->ajax()) return back()->with('error', 'Product out of stock');
			return response()->json(['error' => 'Product out of stock'], 400);
		}
		$cartDetail->update($request->all());
		if (!$request->ajax()) return back()->with('success', 'Amount updated successfully');
		return response()->json([], 204);
	}

	public function show(Request $request, CartDetail $cartDetail)
	{
		if (!$request->ajax()) return view('cartDetails.show', compact('cartDetail'));
		return response()->json(['cartDetail' => $cartDetail], 200);
	}

	public function destroy(Request $request, CartDetail $cartDetail)
	{
		$cartDetail->delete();
		if (!$request->ajax()) return back()->with('success', 'Product removed from cart');
		return response()->json([], 204);
	}
}
