<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Resources\PositionResource;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Position;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
            ], 400);
        }

        $validated = $validator->validated();

        $user = User::factory()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'admin' => $request->has('admin'),
            'phone_number' => $validated['phone_number'],
            'card_number' => $validated['card_number'],
            'position_id' => Position::where('name', $validated['position'])->first()->id,
        ]);

        $token = $user->createToken($user->email, $user->admin ? ['access-control:admin'] : []);

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
            ], 400);
        }

        $validated = $validator->validated();

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return response()->json([
                'error' => 'Hibás email cím',
            ], 404);
        }

        if (Auth::attempt($validated)) {
            $token = $user->createToken($user->email, $user->admin ? ['access-control:admin'] : []);
            return response()->json([
                'token' => $token->plainTextToken,
            ]);
        } else {
            return response()->json([
                'error' => 'Hibás jelszó vagy email cím!',
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        $request->user()->currentAccessToken()->delete();
        return response()->json([], 200);
    }
    public function getPositions(Request $request, string $id = null)
    {
        if (isset($id)) {
            return new PositionResource(Position::with('rooms')->findOrFail($id));
        }

        return PositionResource::collection(Position::with('rooms')->get());
    }
    public function store(StorePositionRequest $request)
    {
        $validated = $request->validated();
        $position = Position::factory()->create($validated);

        return response()->json($position, 201);
    }
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|unique:positions',
        ]);
        $position = Position::findOrFail($id);
        $position->update($validated);

        return response()->json($position, 201);
    }
    public function destroy(Request $request, string $id)
    {
        if ($request->user()->tokenCan('access-control:admin')) {
            $position = Position::findOrFail($id);
            $position->delete();

            return response()->json(status: 204);
        }
        return response()->json([
            'error' => 'Nincs jogosultságod a törléshez!'
        ], 403);
    }
    public function getRooms(Request $request, string $id = null)
    {
        if (isset($id)) {
            return new RoomResource(Room::with('positions')->findOrFail($id));
        }

        return RoomResource::collection(Room::with('positions')->get());
    }
    public function getRoomsPaginated(Request $request)
    {
        return new RoomCollection(Room::with('positions')->paginate(2));
    }
    public function storeRoom(StoreRoomRequest $request)
    {
        $validated = $request->validated();
        $room = Room::factory()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        isset($validated['positions']) ? $room->positions()->sync($validated['positions']) : "";
        return response()->json($room, 201);
    }
}
