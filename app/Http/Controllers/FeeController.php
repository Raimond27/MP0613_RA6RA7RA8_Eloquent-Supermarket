<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    // Display a list of fees with optional filters.
    public function index(Request $request)
    {
        // Build the query for fees.
        $query = Fee::query();

        // Filter by fee name if provided.
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // Filter fees with a start date greater than or equal to the input date.
        if ($request->filled('date_start')) {
            $query->where('date_start', '>=', $request->input('date_start'));
        }

        // Filter fees with an end date less than or equal to the input date.
        if ($request->filled('date_end')) {
            $query->where('date_end', '<=', $request->input('date_end'));
        }

        // Retrieve the filtered fees.
        $fees = $query->get();

        // Return the fee index view with the retrieved fees.
        return view('pages.fee.index', compact('fees'));
    }

    // Store a newly created fee.
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);

        // Create a new fee record using the validated data.
        Fee::create($request->all());

        // Redirect back with a success message.
        return redirect()->back()->with('success', 'Fee created successfully!');
    }

    // Update the specified fee.
    public function update(Request $request, Fee $fee)
    {
        // Validate the incoming data for update.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);

        // Update the fee with the validated data.
        $fee->update($validated);

        // Redirect to the fees list with a success message.
        return redirect()->route('fees.index')->with('success', 'Fee updated successfully!');
    }

    // Remove the specified fee.
    public function destroy(Fee $fee)
    {
        // Delete the fee record.
        $fee->delete();

        // Redirect to the fees list with a success message.
        return redirect()->route('fees.index')->with('success', 'Fee deleted successfully!');
    }
}
