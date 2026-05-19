<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Hold a Category instance for creating new records.
    protected $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    // Display a list of categories with optional filters.
    public function index(Request $request)
    {
        // Get all categories for dropdown selectors.
        $allCategories = Category::all();

        // Begin a query for categories.
        $query = Category::query();

        // Apply a filter for name if provided.
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // Apply a filter for parent category if provided.
        if ($request->filled('parent_category')) {
            $query->where('parent_category', $request->input('parent_category'));
        }

        // Execute the query to get filtered categories.
        $categories = $query->get();

        // Return the view with both filtered and complete lists.
        return view('pages.category.index', compact('categories', 'allCategories'));
    }

    // Store a new category in the database.
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2048',
            'parent_category' => 'nullable|exists:categories,id'
        ]);

        // Create the category using the request data.
        $this->category->create($request->all());

        // Redirect back with a success message.
        return redirect()->back()->with('success', 'Category created successfully!');
    }

    // Update an existing category.
    public function update(Request $request, Category $category)
    {
        // Validate the update data.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_category' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        // Update the category with the validated data.
        $category->update($validated);

        // Redirect to the index with a success message.
        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    // Delete a category.
    public function destroy(Category $category)
    {
        // Remove the category record from the database.
        $category->delete();

        // Redirect to the index with a success message.
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }

    // Recursively retrieve the given category ID plus all its child category IDs.
    public function getCategoryAndChildren($categoryId)
    {
        // Start with the current category.
        $categoryIds = [$categoryId];
        // Get direct children of the current category.
        $children = Category::where('parent_category', $categoryId)->get();

        // Recursively merge child category IDs.
        foreach ($children as $child) {
            $categoryIds = array_merge($categoryIds, $this->getCategoryAndChildren($child->id));
        }

        return $categoryIds;
    }
}
