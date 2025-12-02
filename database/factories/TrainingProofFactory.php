<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TrainingProof;
use App\Models\TrainingRecord;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingProof>
 */
class TrainingProofFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingProof::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'training_id' => TrainingRecord::factory(),
            'user_id' => User::factory(),
            'file_path' => 'training_proofs/' . $this->faker->uuid . '.pdf',
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reviewed_by' => User::factory(),
        ];
    }
}