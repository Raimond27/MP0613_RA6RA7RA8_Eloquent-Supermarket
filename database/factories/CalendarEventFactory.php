<?php

namespace Database\Factories;

use App\Models\CalendarEvent;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalendarEventFactory extends Factory
{
    // The model that this factory creates.
    protected $model = CalendarEvent::class;

    // Define the default state for a CalendarEvent.
    public function definition()
    {
        // Define possible recurrence types.
        $recurrenceTypes = ['none', 'monthly', 'yearly'];
        // Randomly choose a recurrence type.
        $recurrenceType = $this->faker->randomElement($recurrenceTypes);

        // Generate a random upcoming date within the next 30 days.
        $nextOccurrence = Carbon::now()
            ->addDays($this->faker->numberBetween(1, 30))
            ->toDateString();

        return [
            // Set a placeholder user ID (adjust dynamically as needed).
            'user_id' => 1,
            // Generate a random event description.
            'description' => $this->faker->sentence,
            // Set the next occurrence date.
            'next_occurrence' => $nextOccurrence,
            // Set the randomly selected recurrence type.
            'recurrence_type' => $recurrenceType,
        ];
    }

    // After creating a CalendarEvent, attach random products.
    public function configure()
    {
        return $this->afterCreating(function (CalendarEvent $event) {
            // Retrieve all products.
            $products = Product::all();
            if ($products->count() > 0) {
                // Choose a random count between 1 and 3 (or less if fewer products exist).
                $randomCount = rand(1, min(3, $products->count()));
                $selected = $products->random($randomCount);

                // Ensure the selected products are in an array (handle single selection).
                $ids = $selected instanceof \Illuminate\Support\Collection
                    ? $selected->pluck('id')->toArray()
                    : [$selected->id];

                // Attach the selected product IDs to the event.
                $event->products()->attach($ids);
            }
        });
    }
}
