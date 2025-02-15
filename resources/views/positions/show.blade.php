@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Details for Position: {{ $position->name }}</h1>
            <a href="{{ route('positions.index') }}" class="btn btn-secondary">Back to Position List</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <h3>Users in this Position</h3>
                @if ($users->isEmpty())
                    <p class="text-muted">No users are currently assigned to this position.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
