@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Room List</h1>
            @if (auth()->user()->admin)
                <a href="{{ route('rooms.create') }}" class="btn btn-success">Create Room</a>
            @endif
        </div>

        @if (Session::has('room_created'))
            <div class="alert alert-success">Room creation was successful!</div>
        @endif
        @if (Session::has('room_updated'))
            <div class="alert alert-success">Room update was successful!</div>
        @endif
        @if (Session::has('room_deleted'))
            <div class="alert alert-success">Room deletion was successful!</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room Name</th>
                        <th>Authorized Positions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $room->name }}</td>
                            <td>
                                @if ($room->positions->isNotEmpty())
                                    <ul>
                                        @foreach ($room->positions as $position)
                                            <li>{{ $position->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>No authorized positions</em>
                                @endif
                            </td>
                            <td>
                                @if (auth()->user()->admin)
                                    <a href="{{ route('rooms.show', $room) }}" class="btn btn-info btn-sm">Details</a>
                                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this room?');">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No rooms available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
