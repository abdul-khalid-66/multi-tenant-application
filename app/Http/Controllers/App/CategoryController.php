<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('app.product.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $mainCategories = Category::whereNull('subcategory')->get();
        return view('app.product.category.create', compact('mainCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100',
            'is_subcategory' => 'nullable',
            'parent_category' => 'nullable|exists:categories,id'
        ]);

        $isSubcategory = $request->has('is_subcategory'); // Check if checkbox was checked

        Category::create([
            'category_name' => $request->category_name,
            'subcategory' => $isSubcategory ? Category::find($request->parent_category)->category_name : null
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // return view('categories.edit', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('subcategory')->where('id', '!=', $category->id)->get();
        return view('app.product.category.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
