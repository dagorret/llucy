<?php

namespace Database\Factories;

use App\Models\Carrera;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Carrera>
 */
class CarreraFactory extends Factory
{
    protected $model = Carrera::class;

    public function definition(): array
    {
        $plan = Plan::factory();

        return [
            'plan_id' => $plan,
            'codigo' => Str::upper(fake()->bothify('CAR###')),
            'nombre' => fake()->sentence(3),
        ];
    }
}
