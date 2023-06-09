<?php

namespace Database\Factories;

use App\enums\TripStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        ];
    }

    public function validToNewEstimate(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => $this->faker->randomElement(TripStatusEnum::getValidStatusListToNewEstimate()),
            ];
        });
    }

    public function notValidToNewEstimate(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TripStatusEnum::AT_VENDOR,
            ];
        });
    }
}
