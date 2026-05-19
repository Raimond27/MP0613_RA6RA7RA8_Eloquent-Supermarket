<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEventProduct extends Model
{
    use HasFactory;

    // Attributes that can be mass-assigned.
    protected $fillable = ['calendar_event_id', 'product_id'];

    // Define the relationship: This model belongs to a CalendarEvent.
    public function event()
    {
        return $this->belongsTo(CalendarEvent::class);
    }

    // Define the relationship: This model belongs to a Product.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
