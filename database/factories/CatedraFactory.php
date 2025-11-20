<?php

namespace Database\Factories;

use App\Models\Catedra;
use App\Models\Materia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Catedra>
 */
class CatedraFactory extends Factory
{
    protected $model = Catedra::class;

    public function definition(): array
    {
        return [
            'materia_id' => Materia::factory(),
            'codigo' => Str::upper(fake()->bothify('CAT###')),
            'codigo_grupo' => fake()->optional()->bothify('GRP##'),
            'codigo_canal' => fake()->optional()->bothify('CAN##'),
            'modalidad' => fake()->randomElement(['presencial', 'distancia', 'ambas']),
        ];
    }
}
