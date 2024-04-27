<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartRequest;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
	public function store(CartRequest $request)
	{
		$cart = new Cart($request->all());
		$cart->save();
		return response()->json(['status' => 'Cart created successfully', 'cart' => $cart], 201);
	}

	public function show(Request $request, User $user)
	{
		$cart = $user->cart()->with('cartDetails')->first();

		if (!$request->ajax()) return view('carts.show', compact('cart'));
		return response()->json(['cart' => $cart], 200);
	}
}
