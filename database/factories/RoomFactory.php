<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $str = fake()->word();
        while (strlen($str) < 5) {
            $str = fake()->word();
        }
        return [
            'name' => $str,
            'description' => fake()->optional()->text(255),
        ];
    }
}
