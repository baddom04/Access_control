@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Position list</h1>
            @if (auth()->user()->admin)
                <a href="{{ route('positions.create') }}" class="btn btn-success">Create position</a>
            @endif
        </div>
        @if (Session::has('position_created'))
            <div class="alert alert-success">Position creation was successful!</div>
        @endif
        @if (Session::has('position_updated'))
            <div class="alert alert-success">Position update was successful!</div>
        @endif
        @if (Session::has('position_deleted'))
            <div class="alert alert-success">Position delete was successful!</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Position Name</th>
                        <th>Number of People</th>
                        <th>Authorized Rooms</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($positions as $position)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $position->name }}</td>
                            <td>{{ $position->users->count() }}</td>
                            <td>
                                @if ($position->rooms->isNotEmpty())
                                    <ul>
                                        @foreach ($position->rooms as $room)
                                            <li>{{ $room->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>No authorized rooms</em>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('positions.show', $position) }}" class="btn btn-info btn-sm">Details</a>
                                @if (auth()->user()->admin)
                                    <a href="{{ route('positions.edit', $position) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('positions.destroy', $position) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this position?');">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No positions available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
