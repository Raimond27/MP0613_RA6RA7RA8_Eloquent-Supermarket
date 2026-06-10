<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Models\Category;
use App\Models\Fee;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    // Property to hold a Product instance for later use (e.g., for dependency injection or shared methods).
    protected $products;

    // Constructor initializes the Product instance.
    public function __construct()
    {
        $this->products = new Product();
    }

    // Display a paginated list of products with filters for name, price, and category.
    public function index(Request $request)
    {
        // Start a query with eager loading for category and images.
        $query = Product::with(['category', 'images']);

        // Filter products by name using a partial match.
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // Filter products with a base price greater than or equal to the minimum price.
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        // Filter products with a base price less than or equal to the maximum price.
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // If a category filter is set, include all its children categories in the filter.
        if ($request->filled('category_id')) {
            $categoryIds = app(CategoryController::class)->getCategoryAndChildren($request->category_id);
            $query->whereIn('category_id', $categoryIds);
        }

        // Fetch the products with pagination.
        $products = $query->paginate(9);
        // Fetch all categories to populate a dropdown.
        $categories = Category::all();
        // Fetch all fees.
        $fees = Fee::all();

        // Return the product index view with the collected data.
        return view('pages.product.index', compact('products', 'categories', 'fees'));
    }

    // Store a newly created product.
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:2048',
        ]);

        // Create a new product record with the validated data.
        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? '',
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
        ]);

        // Loop through each uploaded image and store it.
        foreach ($request->file('images') as $image) {
            // Save the image to the public disk under the "products" directory.
            $path = $image->store('products', 'public');

            // Create a new image record linked to this product.
            $product->images()->create([
                'image_path' => $path,
            ]);
        }

        // Redirect to the product index page with a success message.
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    // Update an existing product.
    public function update(Request $request, Product $product)
    {
        // Validate the incoming update data.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            // Image files are optional and only validated if present.
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            // Optional fee and fee details.
            'fee_id' => 'nullable|exists:fees,id',
            'variation_type' => 'nullable|in:number,percentage',
            'variation_amount' => 'nullable|numeric',
        ]);

        // Update the product's basic details.
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
        ]);

        // Process new image uploads if available.
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        // Update fee information based on the validated data.
        if ($validated['fee_id']) {
            $product->fees()->sync([
                $validated['fee_id'] => [
                    'variation_type' => $validated['variation_type'],
                    'variation_ammount' => $validated['variation_amount'],
                ],
            ]);
        } else {
            // Detach fees if none is selected.
            $product->fees()->detach();
        }

        // Redirect back to the product index with a success message.
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete a product.
    public function destroy(Product $product)
    {
        // Remove the product record.
        $product->delete();

        // Redirect to the product index with a message.
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    // Export the list of products to an Excel file.
    public function export(Request $request)
    {
        // Download an Excel file generated from the ProductExport class.
        return Excel::download(new ProductExport, 'products.xlsx');
    }

    // Export a product's details as a PDF.
    public function exportPdf(Product $product)
    {
        // Eager load the product's category and fee data.
        $product->load(['category', 'fees']);

        // Load a view into the PDF generator using product data.
        $pdf = Pdf::loadView('pages.product.pdf', compact('product'));

        // Download the generated PDF named after the product's ID.
        return $pdf->download('product_' . $product->id . '.pdf');
    }
}
