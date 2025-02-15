<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;
    public function user_room_entries(): HasMany
    {
        return $this->hasMany(UserRoomEntry::class);
    }
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class);
    }
}
