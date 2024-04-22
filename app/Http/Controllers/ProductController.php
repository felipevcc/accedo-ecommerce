<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
	public function index()
	{
		//
	}

	public function getAllDT()
	{
		$products = Product::with('category', 'image')->query();
		return DataTables::of($products)->toJson();
	}

	public function search(Request $request)
	{
		$request->validate([
			'searchTerm' => ['required', 'string'],
		]);
		$products = Product::available()->search($request->searchTerm)->get();
		return view('products.search', compact('products'));
	}

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		//
	}

	public function show($id)
	{
		//
	}

	public function edit($id)
	{
		//
	}

	public function update(Request $request, $id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}
}
