<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }

        $positions = Position::with(['users', 'rooms'])->get();
        return view('positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        // $this->authorize('create');
        return view('positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        // $this->authorize('create');
        $validated = $request->validate([
            'name' => 'required|unique:positions',
        ]);
        Position::factory()->create($validated);
        Session::flash('position_created');
        return redirect()->route('positions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('view', $position);
        $users = $position->users()->select('name', 'phone_number')->get();
        return view('positions.show', compact('position', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('update', $position);
        return view('positions.edit', ['position' => $position]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('update', $position);
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('positions')->ignore($position->name)
            ],
        ]);
        $position->name = $validated['name'];
        $position->save();
        Session::flash('position_created');
        return redirect()->route('positions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('delete', $position);
        Session::flash('position_deleted');
        $position->delete();
        return redirect()->route('positions.index');
    }
}
