<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    // Allow mass assignment for these fields.
    protected $fillable = ['name', 'date_start', 'date_end'];

    // Define the many-to-many relationship with products using the product_fee pivot table.
    // Include pivot fields: variation_type, variation_ammount, and quantity.
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_fee')
            ->withPivot('variation_type', 'variation_ammount', 'quantity');
    }

    // Scope to retrieve fees that are active at the current time.
    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('date_start', '<=', $now)
            ->where('date_end', '>=', $now);
    }
}
