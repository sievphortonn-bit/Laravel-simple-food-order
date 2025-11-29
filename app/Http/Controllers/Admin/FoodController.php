<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    /**
     * Display list with search + filter.
     */
    public function index(Request $request)
    {
        $query = Food::with('category')->latest();

        // Search food name
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filter category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $foods = $query->paginate(12);
        return view('admin.foods.index', compact('foods'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.foods.create', compact('categories'));
    }

    /**
     * Store new food.
     */
   public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:foods,slug',
            'price'       => 'required|numeric',
            'qty'         => 'required|integer|min:0', // NEW
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'nullable|boolean',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_food.' . $request->image->extension();
            $request->image->move(public_path('uploads/foods'), $imageName);
        }

        Food::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'price'       => $request->price,
            'qty'         => $request->qty, // NEW
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image'       => $imageName,
            'is_active'   => $request->is_active ? 1 : 0,
        ]);

        return redirect()->route('admin.foods.index')->with('success', 'Food created successfully.');
    }


    /**
     * Show edit form.
     */
    public function edit(Food $food)
{
    $categories = Category::all();
    return view('admin.foods.edit', compact('food', 'categories'));
}

    /**
     * Update food.
     */
    public function update(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:foods,slug,' . $food->id,
            'price'       => 'required|numeric',
            'qty'         => 'required|integer|min:0', // NEW
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'nullable|boolean',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $imageName = $food->image;

        if ($request->hasFile('image')) {
            if ($food->image && File::exists(public_path('uploads/foods/' . $food->image))) {
                File::delete(public_path('uploads/foods/' . $food->image));
            }

            $imageName = time() . '_food.' . $request->image->extension();
            $request->image->move(public_path('uploads/foods'), $imageName);
        }

        $food->update([
            'name'        => $request->name,
            'slug'        => $slug,
            'price'       => $request->price,
            'qty'         => $request->qty, // NEW
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image'       => $imageName,
            'is_active'   => $request->is_active ? 1 : 0,
        ]);

        return redirect()->route('admin.foods.index')->with('success', 'Food updated successfully.');
    }


    /**
     * Delete food.
     */
    public function destroy($id)
    {
        $food = Food::findOrFail($id);

        if ($food->image && File::exists(public_path('uploads/foods/' . $food->image))) {
            File::delete(public_path('uploads/foods/' . $food->image));
        }

        $food->delete();

        return redirect()->route('admin.foods.index')->with('success', 'Food deleted successfully.');
    }
}
