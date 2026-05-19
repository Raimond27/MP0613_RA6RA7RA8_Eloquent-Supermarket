<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection
{
    // Return a collection of products transformed for Excel export.
    public function collection()
    {
        // Load products with their associated category and fees.
        $products = Product::with(['category', 'fees'])->get();

        // Transform each product into an array with the desired columns.
        $exportData = $products->map(function ($product) {
            // Format fees as a string showing each fee's name, variation type, and amount.
            $fees = $product->fees->map(function ($fee) {
                $pivot = $fee->pivot;
                return $fee->name . ' (' . $pivot->variation_type . ' ' . $pivot->variation_ammount . ')';
            })->implode(', ');

            return [
                'ID' => $product->id,
                'Name' => $product->name,
                'Description' => $product->description,
                'Category' => $product->category ? $product->category->name : '',
                'Base Price' => $product->price,
                'Final Price' => $product->final_price,
                'Fees' => $fees,
            ];
        });

        return $exportData;
    }

    // Define Excel column headings.
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Category',
            'Base Price',
            'Final Price',
            'Fees',
        ];
    }
}
