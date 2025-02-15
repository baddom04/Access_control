<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }

        $rooms = Room::with('positions')->get();
        return view('rooms.index', compact('rooms'));
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
        $positions = Position::all();
        return view('rooms.create', compact('positions'));
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
            'name' => 'required|min:5',
            'description' => 'max:255',
            'image' => 'required|file|mimes:jpg,png|max:2048',
            'positions' => 'nullable|array',
            'positions.*' => 'exists:positions,id',
        ]);
        $image_path = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = 'image_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($image_path, $file->get());
        }
        $room = Room::factory()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $image_path == '' ? null : $image_path,
        ]);
        isset($validated['positions']) ? $room->positions()->sync($validated['positions']) : "";
        Session::flash('room_created');
        return redirect()->route('rooms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('view', $room);
        $entries = $room->user_room_entries()
            ->with(['user', 'user.position'])
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        return view('rooms.show', compact('room', 'entries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('update', $room);
        $positions = Position::all();
        return view('rooms.edit', compact('room', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('update', $room);
        $validated = $request->validate([
            'name' => 'required|min:5',
            'description' => 'max:255',
            'image' => 'file|mimes:jpg,png|max:2048',
            'positions' => 'nullable|array',
            'positions.*' => 'exists:positions,id',
        ]);
        $image_path = $room->image;
        if (isset($validated['image'])) {
            $image_path = null;
        } elseif ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = 'image_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($image_path, $file->get());
        }

        if ($image_path != $room->image && $room->image != null) {
            Storage::disk('public')->delete($room->image);
        }
        $room->name = $validated['name'];
        $room->description = $validated['description'];
        $room->image = ($image_path == '' ? null : $image_path);
        isset($validated['positions']) ? $room->positions()->sync($validated['positions']) : "";
        Session::flash('room_updated');
        return redirect()->route('rooms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('delete', $room);
        Session::flash('room_deleted');
        $room->delete();
        return redirect()->route('rooms.index');
    }
}
