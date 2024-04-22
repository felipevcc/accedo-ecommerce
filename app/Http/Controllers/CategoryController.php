<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Category\CategoryRequest;

class CategoryController extends Controller
{
	public function home()
	{
		// Get the first 5 products for each category
		$categories = Category::with(['products' => function ($query) {
			$query->available()->with('image')->limit(5);
		}])->get();
		return view('home', compact('categories'));
	}

	public function index(Request $request)
	{
		$categories = Category::get();
		if (!$request->ajax()) return view('categories.index');
		return response()->json(['categories' => $categories], 200);
	}

	public function getAllDT()
	{
		$categories = Category::query();
		return DataTables::of($categories)->toJson();
	}

	public function getProducts(Request $request, Category $category)
	{
		// Get all products in a category
		$products = $category->products()->available()->with('image')->get();
		if (!$request->ajax()) return view('categories.products', compact('category', 'products'));
		return response()->json(['products' => $products], 200);
	}

	public function store(CategoryRequest $request)
	{
		$category = new Category($request->all());
		$category->save();
		if (!$request->ajax()) return back()->with('success', 'Category created successfully');
		return response()->json(['status' => 'Category created successfully', 'category' => $category], 201);
	}

	public function show(Request $request, Category $category)
	{
		if (!$request->ajax()) return view();
		return response()->json(['category' => $category], 200);
	}

	public function update(CategoryRequest $request, Category $category)
	{
		$category->update($request->all());
		if (!$request->ajax()) return back()->with('success', 'Category updated successfully');
		return response()->json([], 204);
	}

	public function destroy(Request $request, Category $category)
	{
		$category->delete();
		if (!$request->ajax()) return back()->with('success', 'Category deleted successfully');
		return response()->json([], 204);
	}
}
