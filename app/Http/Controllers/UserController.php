<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\User;
use App\Models\Room;
use App\Models\UserRoomEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        return view('users.index', ['users' => User::all()]);
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
        return view('users.create', ['positions' => Position::all()]);
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone_number' => ['required', 'regex:/^06-\d{2}-\d{3}-\d{4}$/'],
            'card_number' => ['required', 'regex:/^[1-9A-Z]{16}$/'],
            'position' => [
                'required',
                Rule::in(Position::all()->pluck('name')->toArray())
            ],
        ]);
        User::factory()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'admin' => $request->has('admin'),
            'phone_number' => $validated['phone_number'],
            'card_number' => $validated['card_number'],
            'position_id' => Position::where('name', $validated['position'])->first()->id,
        ]);
        Session::flash('user_created');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('view', $user);
        $entries = UserRoomEntry::where('user_id', $user->id)
            ->join('rooms', 'rooms.id', '=', 'user_room_entries.room_id')
            ->orderBy('created_at', 'desc')
            ->select('user_room_entries.*', 'rooms.name as room_name')
            ->paginate(2);

        return view('users.show', ['entries' => $entries]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('update', $user);
        return view('users.edit', [
            'positions' => Position::all(),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('update', $user);
        $validated = $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'required',
            'phone_number' => ['required', 'regex:/^06-\d{2}-\d{3}-\d{4}$/'],
            'card_number' => ['required', 'regex:/^[1-9A-Z]{16}$/'],
            'position' => [
                'required',
                Rule::in(Position::all()->pluck('name')->toArray())
            ],
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->phone_number = $validated['phone_number'];
        $user->card_number = $validated['card_number'];
        $user->position_id = Position::where('name', $validated['position'])->first()->id;
        $user->save();
        Session::flash('user_updated');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!Auth::check()) {
            return view('welcome', ['room_count' => Room::count(), 'user_count' => User::count()]);
        }
        $this->authorize('delete', $user);
        Session::flash('user_deleted');
        $user->delete();
        return redirect()->route('users.index');
    }
}
