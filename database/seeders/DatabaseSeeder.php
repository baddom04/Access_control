<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\Room;
use App\Models\User;
use App\Models\UserRoomEntry;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $rooms = Room::factory(5)->create();
        $positions = Position::factory(4)->has(User::factory(3))->create();

        foreach ($rooms as $room) {
            $room->positions()->sync(
                $positions->random(rand(1, $positions->count()))
            );
        }

        foreach ($rooms as $room) {
            $userRoomEntries = UserRoomEntry::factory(5)->create();
            foreach ($userRoomEntries as $userRoomEntry) {
                $userRoomEntry->room()->associate($room)->save();

                $user = User::all()->random();
                $userRoomEntry->user()->associate($user)->save();

                $acceptablePositions = $room->positions->pluck('id')->toArray();

                $userRoomEntry->successful = in_array($user->position_id, $acceptablePositions);
                $userRoomEntry->save();
            }
        }

        User::factory()->create([
            'name' => 'Domi',
            'email' => 'asd@gmail.com',
            'password' => 'password',
            'admin' => true,
            'phone_number' => '06-20-357-7192',
            'card_number' => 'JREJEOJREJEOJREJ',
            'position_id' => Position::all()->first()->id,
        ]);
    }
}
