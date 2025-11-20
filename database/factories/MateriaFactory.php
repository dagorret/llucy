<?php

namespace Database\Factories;

use App\Models\Materia;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Materia>
 */
class MateriaFactory extends Factory
{
    protected $model = Materia::class;

    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'nombre' => fake()->sentence(3),
            'codigo' => Str::upper(fake()->bothify('MAT###')),
            'codigo_uti' => fake()->optional()->bothify('UTI###'),
            'cuatrimestre' => fake()->numberBetween(1, 3),
        ];
    }
}
