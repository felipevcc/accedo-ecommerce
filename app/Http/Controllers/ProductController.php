<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\UploadImage;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
	use UploadImage;

	public function index(Request $request)
	{
		$products = Product::available()->with('category', 'image')->get();
		if (!$request->ajax()) return view('admin.products.index', compact('products'));
		return response()->json(['products' => $products], 200);
	}

	public function getAllDT()
	{
		$products = Product::with('category', 'image');
		return DataTables::of($products)->toJson();
	}

	public function search(Request $request)
	{
		$request->validate([
			'searchTerm' => ['required', 'string'],
		]);
		$products = Product::available()->search($request->searchTerm)->with('category', 'image')->get();
		if (!$request->ajax()) return view('products.search', compact('products'));
		return response()->json(['products' => $products], 200);
	}

	public function store(ProductRequest $request)
	{
		try {
			DB::beginTransaction();
			$product = new Product($request->all());
			$product->save();
			$this->uploadImage($product, $request);
			DB::commit();
			if (!$request->ajax()) return back()->with('success', 'Product created successfully');
			return response()->json(['status' => 'Product created successfully', 'product' => $product], 201);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}

	public function show(Request $request, Product $product)
	{
		$product->load('category', 'image');
		if (!$request->ajax()) return view('products.info', compact('product'));
		return response()->json(['product' => $product], 200);
	}

	public function update(ProductUpdateRequest $request, Product $product)
	{
		try {
			DB::beginTransaction();
			$product->update($request->all());
			$this->uploadImage($product, $request);
			DB::commit();
			if (!$request->ajax()) return back()->with('success', 'Product updated successfully');
			return response()->json([], 204);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}

	public function destroy(Request $request, Product $product)
	{
		$product->delete();
		$this->deleteImage($product);
		if (!$request->ajax()) return back()->with('success', 'Product deleted successfully');
		return response()->json([], 204);
	}
}
