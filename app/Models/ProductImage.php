<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // Allow mass assignment for product_id and image_path.
    protected $fillable = ['product_id', 'image_path'];

    // Define the relationship: This image belongs to a Product.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
