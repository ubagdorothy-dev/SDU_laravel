<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TrainingRecord;
use App\Models\User;
use App\Models\Office;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingRecord>
 */
class TrainingRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'completed', 'upcoming']),
            'venue' => $this->faker->city,
            'proof_uploaded' => $this->faker->boolean(40), // 40% chance of having proof uploaded
            'office_code' => Office::factory(),
            'nature' => $this->faker->randomElement(['Internal', 'External']),
            'scope' => $this->faker->randomElement(['Local', 'Regional', 'National', 'International']),
        ];
    }
}