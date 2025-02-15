@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Room Update Form</h5>
                        <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Enter name:</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Type something..."
                                    value="{{ old('name', $room->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Enter Text:</label>
                                <input type="text" id="description" name="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Type something..." value="{{ old('description', $room->description) }}">
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group row mb-3">
                                <label for="cover_image" class="col-sm-2 col-form-label">Image</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <input type="file"
                                                    class="form-control-file @error('image') is-invalid @enderror"
                                                    id="image" name="image">
                                                @error('image')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>
                                            <div id="preview" class="col-12 d-none">
                                                <p>Cover preview:</p>
                                                <img id="preview_image" src="#" alt="Cover preview" width="300px">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="positions" class="form-label">Authorized Positions:</label>
                                <select id="positions" name="positions[]"
                                    class="form-control @error('positions') is-invalid @enderror" multiple>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}"
                                            @if (is_array(old('positions', $room->positions->pluck('id')->toArray())) &&
                                                    in_array($position->id, old('positions', $room->positions->pluck('id')->toArray()))) selected @endif>
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('positions')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) key to
                                    select multiple positions.</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
