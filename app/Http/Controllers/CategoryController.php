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
		$categories = Category::has('products')->get();
		foreach ($categories as $category) {
			$category->load(['products' => function ($query) {
				$query->available()->with('image')->take(5);
			}]);
		}
		return view('home', compact('categories'));
	}

	public function index(Request $request)
	{
		$categories = Category::get();
		if (!$request->ajax()) return view('admin.categories.index');
		return response()->json(['categories' => $categories], 200);
	}

	public function getAllDT()
	{
		$categories = Category::query();
		return DataTables::of($categories)->toJson();
	}

	public function loadProducts(Request $request, Category $category)
	{
		// Load all products in a category
		$category->load(['products' => function ($query) {
			$query->available()->with('image');
		}]);
		if (!$request->ajax()) return view('categories.products', compact('category'));
		return response()->json(['category' => $category], 200);
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
