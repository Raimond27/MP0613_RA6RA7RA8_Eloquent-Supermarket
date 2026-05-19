@extends('layout')

@section('content')
    <div class="container mt-5">
        <!-- Page Title -->
        <h3 class="text-center">Fees</h3>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Fee Creation Form -->
                <div class="border p-4 bg-light rounded">
                    <form method="POST" action="{{ route('fees.store') }}">
                        @csrf
                        <div class="row">
                            <!-- Fee Name Input -->
                            <div class="col-md">
                                <label class="form-label">Fee Name</label>
                                <input type="text" class="form-control" name="name">
                                @if ($errors->has('name'))
                                    <div class="text-danger mt-1">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <!-- Start Date Input -->
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="date_start" id="startDate" required>
                                </div>
                            </div>
                            <!-- End Date Input -->
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="date_end" id="endDate" required>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>

                <!-- Fee Filter Form -->
                <div class="border p-3 bg-light rounded mt-4">
                    <form method="GET" action="{{ route('fees.index') }}">
                        <div class="row">
                            <!-- Filter by Fee Name -->
                            <div class="col-md-4">
                                <label class="form-label">Filter by Name</label>
                                <input type="text" class="form-control" name="name" value="{{ request('name') }}">
                            </div>
                            <!-- Filter by Start Date -->
                            <div class="col-md-4">
                                <label class="form-label">Start Date (From)</label>
                                <input type="date" class="form-control" name="date_start"
                                    value="{{ request('date_start') }}">
                            </div>
                            <!-- Filter by End Date -->
                            <div class="col-md-4">
                                <label class="form-label">End Date (Until)</label>
                                <input type="date" class="form-control" name="date_end"
                                    value="{{ request('date_end') }}">
                            </div>
                        </div>
                        <!-- Filter Action Buttons -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('fees.index') }}" class="btn btn-secondary mt-2">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Fee Listing Table -->
                <div class="container mt-5">
                    <table class="table table-hover table-responsive">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-start ps-5">Name</th>
                                <th scope="col" class="text-start">Start Date</th>
                                <th scope="col" class="text-start">End Date</th>
                                <th scope="col" class="text-end pe-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($fees->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No fees found for this search.</td>
                                </tr>
                            @else
                                @foreach ($fees as $fee)
                                    <tr>
                                        <!-- Fee Details -->
                                        <td>{{ $fee->name }}</td>
                                        <td>{{ $fee->date_start }}</td>
                                        <td>{{ $fee->date_end }}</td>
                                        <!-- Action Buttons: Edit and Delete -->
                                        <td class="text-end gap-2">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $fee->id }}">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $fee->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <!-- Include Edit Fee Modal Component -->
                                            @include('components.edit-fee-modal', [
                                                'fee' => $fee,
                                            ])
                                            <!-- Include Confirmation Modal Component for Deletion -->
                                            @include('components.confirmation-modal', [
                                                'modalId' => "deleteModal{$fee->id}",
                                                'title' => 'Confirm Delete',
                                                'message' => 'Are you sure you want to delete this fee?',
                                                'action' => route('fees.destroy', $fee->id),
                                                'method' => 'DELETE',
                                                'confirmButton' => 'Delete',
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
