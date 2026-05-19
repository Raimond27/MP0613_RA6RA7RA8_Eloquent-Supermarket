<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    // Mass-assignable attributes.
    protected $fillable = [
        'user_id',
        'description',
        'next_occurrence',
        'recurrence_type'
    ];

    // Specify which attributes should be treated as dates.
    protected $dates = ['next_occurrence'];

    // Cast attributes to native types.
    protected $casts = [
        'next_occurrence' => 'date',
    ];

    // Relationship: This event belongs to a user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: This event belongs to many products via a pivot table.
    public function products()
    {
        return $this->belongsToMany(Product::class, 'calendar_event_products');
    }

    // Calculate the next occurrence based on a given date and recurrence type.
    public static function calculateNextOccurrence($date, $recurrenceType)
    {
        $now = Carbon::now();
        $current = Carbon::parse($date);

        if ($recurrenceType === 'none') {
            // One-time event; return the original date.
            return $current;
        } elseif ($recurrenceType === 'monthly') {
            // Event recurs monthly; add one month.
            return $current->addMonth();
        } elseif ($recurrenceType === 'yearly') {
            // Event recurs yearly; add one year.
            return $current->addYear();
        }

        // Return null for an unrecognized recurrence type.
        return null;
    }
}
