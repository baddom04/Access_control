@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Entries for Room: {{ $room->name }}</h1>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back to Room List</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>User Name</th>
                        <th>Phone Number</th>
                        <th>Position</th>
                        <th>Entry Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td>{{ $loop->iteration + ($entries->currentPage() - 1) * $entries->perPage() }}</td>
                            <td>{{ $entry->created_at }}</td>
                            <td>{{ $entry->user->name }}</td>
                            <td>{{ $entry->user->phone_number }}</td>
                            <td>{{ $entry->user->position->name ?? 'N/A' }}</td>
                            <td>{{ $entry->successful ? 'Successful' : 'Unsuccessful' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No entries found for this room.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $entries->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
