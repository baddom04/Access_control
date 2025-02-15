@extends('layouts.app')
@section('title', 'Posts')

@section('content')
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-12 col-md-8">
                <h1>Welcome to the access control system!</h1>
                <p>This application is used to manage workers, jobs and rooms in a card reader access control system. The
                    administrators can add new workers, jobs and rooms and manage their data. All
                    login attempt is recorded, so the login logs can be reviewed.
                </p>
            </div>
            <div class="col-12 col-md-4">
                <div class="float-lg-end">
                    <a href="{{ route('rooms.index') }}" role="button" class="btn btn-sm btn-success mb-1">Rooms</a>
                    <a href="{{ route('positions.index') }}" role="button" class="btn btn-sm btn-success mb-1">Positions</a>
                    <a href="{{ route('users.index') }}" role="button" class="btn btn-sm btn-success mb-1">Users</a>
                </div>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-12 col-lg-9">

            </div>
            <div class="col-12 col-lg-3">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card bg-light">
                            <div class="card-header">
                                Statistics
                            </div>
                            <div class="card-body">
                                <div class="small">
                                    <ul class="fa-ul">
                                        <li><span class="fa-li"><i class="fas fa-user"></i></span>Users:
                                            {{ $user_count }}</li>
                                        <li><span class="fa-li"><i class="fas fa-layer-group"></i></span>Rooms:
                                            {{ $room_count }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
