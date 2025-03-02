<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),  // Automatically create a User for each Instructor
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->firstName(),  // Optional field
            'last_name' => $this->faker->lastName(),
            'suffix' => $this->faker->optional()->suffix(),  // Optional field
        ];
    }
}
