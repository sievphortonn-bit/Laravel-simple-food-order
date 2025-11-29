<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    // SHOW ALL CATEGORIES
    public function index()
    {
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('admin.category.create');
    }

    // STORE CATEGORY
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'slug' => 'required|unique:categories,slug',
            'description' => 'nullable'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    // EDIT PAGE
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    // UPDATE CATEGORY
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|min:2',
            'slug' => 'required|unique:categories,slug,'.$category->id,
            'description' => 'nullable'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    // DELETE CATEGORY
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
