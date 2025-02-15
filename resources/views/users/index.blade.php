@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>User List</h1>
            @if (auth()->user()->admin)
                <a href="{{ route('users.create') }}" class="btn btn-success">Create user</a>
            @endif
        </div>

        @if (Session::has('user_updated'))
            <div class="alert alert-success">User update was successful!</div>
        @endif
        @if (Session::has('user_created'))
            <div class="alert alert-success">User creation was successful!</div>
        @endif
        @if (Session::has('user_deleted'))
            <div class="alert alert-success">User deletion was successful!</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->position->name }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>
                            @if (auth()->user()->admin)
                                <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">Details</a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
