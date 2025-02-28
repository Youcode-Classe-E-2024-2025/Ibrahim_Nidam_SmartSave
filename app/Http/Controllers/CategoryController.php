<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'color' => 'required|string|size:7',
            'type' => 'required|in:income,expense',
        ]);

        Category::create([
            'name'    => $request->name,
            'color'   => $request->color,
            'type'    => $request->type,
            'user_id' => Auth::id(), // Associate category with the current user's ID
        ]);

        return redirect('/dashboard')->with('success', 'Category added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'color' => 'required|string|size:7',
            'type' => 'required|in:income,expense',
        ]);

        $category->update($request->all());

        return redirect('/dashboard')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect('/dashboard')->with('success', 'Category deleted successfully.');
    }
}
