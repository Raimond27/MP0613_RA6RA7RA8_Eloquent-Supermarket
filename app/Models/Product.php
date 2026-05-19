<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    // Define table name and primary key
    protected $table = 'products';
    protected $primaryKey = 'id';

    // Attributes that can be mass-assigned
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price'
    ];

    // Relationship: A product belongs to one category.
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: A product has many associated images.
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Relationship: A product can have many fees with extra pivot data.
    public function fees()
    {
        return $this->belongsToMany(Fee::class, 'product_fee')
            ->withPivot('variation_type', 'variation_ammount', 'quantity');
    }

    // Accessor: Calculate the final price by applying an active fee (if one exists).
    public function getFinalPriceAttribute()
    {
        $basePrice = $this->price;

        // Find an active fee for this product (if available).
        $activeFee = $this->fees()->active()->first();

        if ($activeFee) {
            $variationType = $activeFee->pivot->variation_type;
            $variationAmmount = $activeFee->pivot->variation_ammount;

            if ($variationType === 'percentage') {
                // For a percentage variation, adjust the price accordingly.
                $basePrice *= (1 + $variationAmmount / 100);
            } elseif ($variationType === 'number') {
                // For a numeric variation, add the specified amount.
                $basePrice += $variationAmmount;
            }
        }

        return $basePrice;
    }
}
