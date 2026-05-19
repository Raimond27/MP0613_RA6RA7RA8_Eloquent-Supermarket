@extends('layout')

@section('content')
    @php
        // Get the "expanded_day" parameter from the request, if provided
        $expanded_day = request('expanded_day');
    @endphp

    <div class="container mt-5">
        <!-- Calendar Title -->
        <h2 class="text-center">Calendar</h2>

        <!-- Navigation Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Previous Month Button -->
            <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                class="btn btn-outline-primary">&laquo; Prev</a>

            <!-- Center Navigation: "Today" Button and Date Selection Form -->
            <div class="d-flex align-items-center">
                <a href="{{ route('calendar.index', ['year' => now()->year, 'month' => now()->month]) }}"
                    class="btn btn-outline-secondary me-2">Today</a>
                <form method="GET" action="{{ route('calendar.index') }}" class="d-flex">
                    <!-- Year Selector -->
                    <select name="year" class="form-select me-2" style="width: 100px;">
                        @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                            <option value="{{ $y }}" {{ $y == $selectedDate->year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                    <!-- Month Selector -->
                    <select name="month" class="form-select me-2" style="width: 150px;">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $selectedDate->month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-primary">Go</button>
                </form>
            </div>

            <!-- Next Month Button -->
            <a href="{{ route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                class="btn btn-outline-primary">Next &raquo;</a>
        </div>

        <!-- "Add Event" Button to initiate event creation -->
        <a href="{{ route('calendar.index', array_merge(request()->query(), ['edit' => 'new'])) }}"
            class="btn btn-success mb-3">Add Event</a>

        <!-- Calendar Table -->
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <!-- Day Headings -->
                    <th class="text-center">Sun</th>
                    <th class="text-center">Mon</th>
                    <th class="text-center">Tue</th>
                    <th class="text-center">Wed</th>
                    <th class="text-center">Thu</th>
                    <th class="text-center">Fri</th>
                    <th class="text-center">Sat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weeks as $week)
                    <tr>
                        @foreach ($week as $day)
                            <!-- Calendar Day Cell -->
                            <td class="text-center align-top p-1"
                                style="width: 100px; height: {{ ($expanded_day ?? null) == $day ? 'auto' : '100px' }};">
                                <!-- Display the day number if available -->
                                <div>{{ $day ?? '' }}</div>
                                @if ($day && isset($eventsByDay[$day]))
                                    @php $count = count($eventsByDay[$day]); @endphp
                                    <!-- Events Container with limited width -->
                                    <div class="d-flex flex-wrap justify-content-center overflow-hidden"
                                        style="max-width: 90px;">
                                        @if (($expanded_day ?? null) == $day)
                                            <!-- Expanded View: Display all events for the day -->
                                            @foreach ($eventsByDay[$day] as $event)
                                                <a href="{{ route('calendar.index', array_merge(request()->query(), ['edit' => $event->id])) }}"
                                                    class="btn btn-link p-0 text-decoration-none badge bg-primary text-truncate m-1"
                                                    data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                                                    title="Event Details"
                                                    data-bs-content="{{ $event->description }}<br><strong>Total Price: ${{ number_format($event->products->sum('final_price'), 2) }}</strong>">
                                                    {{ \Illuminate\Support\Str::limit($event->description, 8) }}
                                                </a>
                                            @endforeach
                                            @php
                                                // Remove the "expanded_day" parameter for collapsing view
                                                $collapsedQuery = request()->except('expanded_day');
                                            @endphp
                                            <a href="{{ route('calendar.index', $collapsedQuery) }}"
                                                class="badge bg-info text-truncate m-1">Collapse</a>
                                        @else
                                            <!-- Compact View: Display first two events only -->
                                            @foreach ($eventsByDay[$day] as $index => $event)
                                                @if ($index < 2)
                                                    <a href="{{ route('calendar.index', array_merge(request()->query(), ['edit' => $event->id])) }}"
                                                        class="btn btn-link p-0 text-decoration-none badge bg-primary text-truncate m-1"
                                                        data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                                                        title="Event Details"
                                                        data-bs-content="{{ $event->description }}<br><strong>Total Price: ${{ number_format($event->products->sum('final_price'), 2) }}</strong>">
                                                        {{ \Illuminate\Support\Str::limit($event->description, 8) }}
                                                    </a>
                                                @endif
                                            @endforeach
                                            <!-- If more than two events, show link to expand -->
                                            @if ($count > 2)
                                                <a href="{{ route('calendar.index', array_merge(request()->query(), ['expanded_day' => $day])) }}"
                                                    class="badge bg-secondary text-truncate m-1">
                                                    +{{ $count - 2 }}
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Include the Event Modal Component -->
        @include('components.event-modal')
    </div>

    @if (request()->has('edit'))
        <!-- Auto-display the Event Modal if "edit" parameter is present -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                myModal.show();
            });
        </script>
    @endif

    <!-- Initialize Popovers for Event Badges -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>
@endsection
