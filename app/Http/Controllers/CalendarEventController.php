<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarEventController extends Controller
{
    // Display the calendar view with events.
    public function index(Request $request)
    {
        // Get the year and month from the query string or default to the current date.
        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        // Create a date instance for the first day of the selected month.
        $selectedDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $selectedDate->daysInMonth;
        $startDayOfWeek = $selectedDate->dayOfWeek;

        // Build the calendar grid as an array of weeks.
        $weeks = [];
        $week = [];
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $week[] = null;
        }
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $week[] = $day;
            if (count($week) === 7) {
                $weeks[] = $week;
                $week = [];
            }
        }
        if (count($week) > 0) {
            while (count($week) < 7) {
                $week[] = null;
            }
            $weeks[] = $week;
        }

        // Determine the previous and next month.
        $prevMonth = $selectedDate->copy()->subMonth();
        $nextMonth = $selectedDate->copy()->addMonth();

      $allEvents = CalendarEvent:: all();

        // Prepare events organized by day.
        $eventsByDay = [];
        foreach ($allEvents as $event) {
            $eventDate = $event->next_occurrence;
            if ($event->recurrence_type === 'none') {
                if ($eventDate->year == $year && $eventDate->month == $month) {
                    $day = $eventDate->day;
                    $eventsByDay[$day][] = $event;
                }
            } elseif ($event->recurrence_type === 'monthly') {
                $day = $eventDate->day;
                if ($day <= $daysInMonth) {
                    $eventsByDay[$day][] = $event;
                }
            } elseif ($event->recurrence_type === 'yearly') {
                if ($eventDate->month == $month && $eventDate->day <= $daysInMonth) {
                    $day = $eventDate->day;
                    $eventsByDay[$day][] = $event;
                }
            }
        }

        // Check if the edit mode is active and load the event to be edited.
        $editEvent = null;
        if ($request->has('edit')) {
            if ($request->input('edit') !== 'new') {
                $editEvent = CalendarEvent::find($request->input('edit'));
            }
        }

        // Retrieve all products.
        $products = Product::all();

        // Return the calendar view with prepared data.
        return view('pages.calendar.index', compact(
            'weeks',
            'selectedDate',
            'prevMonth',
            'nextMonth',
            'year',
            'month',
            'eventsByDay',
            'editEvent',
            'products'
        ));
    }

    // Store the new event in storage.
    public function store(Request $request)
    {
        // Validate request data.
        $data = $request->validate([
            'description' => 'required|string',
            'next_occurrence' => 'required|date',
            'recurrence_type' => 'required|in:none,monthly,yearly',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        // Create the event for the current user.
        $event = CalendarEvent::create([
            'user_id' => Auth::id(),
            'description' => $data['description'],
            'next_occurrence' => $data['next_occurrence'],
            'recurrence_type' => $data['recurrence_type'],
        ]);

        // Sync selected products to the event.
        if (isset($data['products'])) {
            $event->products()->sync($data['products']);
        }

        // Remove the 'edit' query parameter for clean redirection.
        $redirectQuery = $request->query();
        unset($redirectQuery['edit']);

        // Redirect back to the calendar with a success message.
        return redirect()->route('calendar.index', $redirectQuery)
            ->with('success', 'Event created successfully!');
    }

    // Update an existing event.
    public function update(Request $request, CalendarEvent $calendar)
    {
        // Validate the update data.
        $data = $request->validate([
            'description' => 'required|string',
            'next_occurrence' => 'required|date',
            'recurrence_type' => 'required|in:none,monthly,yearly',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        // Update event details.
        $calendar->update([
            'description' => $data['description'],
            'next_occurrence' => $data['next_occurrence'],
            'recurrence_type' => $data['recurrence_type'],
        ]);

        // Sync associated products.
        if (isset($data['products'])) {
            $calendar->products()->sync($data['products']);
        } else {
            $calendar->products()->sync([]);
        }

        // Remove the 'edit' query parameter.
        $redirectQuery = $request->query();
        unset($redirectQuery['edit']);

        // Redirect back with a success message.
        return redirect()->route('calendar.index', $redirectQuery)
            ->with('success', 'Event updated successfully!');
    }

    // Delete an event.
    public function destroy(Request $request, CalendarEvent $calendar)
    {
        // Delete the event.
        $calendar->delete();

        // Remove the 'edit' parameter for redirection.
        $redirectQuery = $request->query();
        unset($redirectQuery['edit']);

        // Redirect back with a success message.
        return redirect()->route('calendar.index', $redirectQuery)
            ->with('success', 'Event deleted successfully!');
    }

    // Generate event occurrences for display purposes.
    private function getOccurrences(CalendarEvent $event, int $yearsAhead = 5)
    {
        $now = Carbon::now();
        $occurrences = [];

        // Start with the first occurrence.
        $current = Carbon::parse($event->next_occurrence);

        // Add past occurrences while the current occurrence is in the past.
        while ($current->isPast()) {
            $occurrences[] = $current->copy();
            $current = $this->getNextOccurrence($event, $current);
        }

        // Generate future occurrences for a specified number of years.
        for ($i = 0; $i < $yearsAhead; $i++) {
            $occurrences[] = $current->copy();
            $current = $this->getNextOccurrence($event, $current);
        }

        return $occurrences;
    }

    // Get the next occurrence based on the event's recurrence type.
    private function getNextOccurrence(CalendarEvent $event, Carbon $current)
    {
        return match ($event->recurrence_type) {
            'monthly' => $current->addMonth(),
            'yearly' => $current->addYear(),
            default => null,
        };
    }
}
