@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center">User Creation Form</h2>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <!-- Name field -->
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <!-- Email field -->
                                <label for="email">Email:</label>
                                <input type="text" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <!-- Password field -->
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" value="{{ old('password') }}"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group form-check">
                                <!-- Administrator boolean (Checkbox) -->
                                <input type="checkbox" id="admin" name="admin" class="form-check-input"
                                    @if (old('admin')) checked @endif>
                                <label for="admin" class="form-check-label">Administrator</label>
                            </div>

                            <div class="form-group">
                                <!-- Phone Number field -->
                                <label for="phone_number">Phone Number:</label>
                                <input type="tel" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number') }}" placeholder="06-20-200-2000"
                                    class="form-control @error('phone_number') is-invalid @enderror">
                                @error('phone_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <!-- Card Number field -->
                                <label for="card_number">Card Number:</label>
                                <input type="text" id="card_number" name="card_number" value="{{ old('card_number') }}"
                                    class="form-control @error('card_number') is-invalid @enderror">
                                @error('card_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <!-- Position Combobox -->
                                <label for="position">Position:</label>
                                <select id="position" name="position"
                                    class="form-control @error('position') is-invalid @enderror">
                                    @foreach ($positions as $pos)
                                        <option value="{{ $pos->name }}" @selected(old('position') == $pos->name)>
                                            {{ $pos->name }}</option>
                                    @endforeach
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Create user</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .form-group {
        margin-bottom: 1.5rem;
        /* Add space between form groups */
    }
</style>
