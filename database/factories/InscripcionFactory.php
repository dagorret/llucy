<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Catedra;
use App\Models\Inscripcion;
use App\Models\Materia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inscripcion>
 */
class InscripcionFactory extends Factory
{
    protected $model = Inscripcion::class;

    public function definition(): array
    {
        $materia = Materia::factory();

        return [
            'alumno_id' => Alumno::factory(),
            'materia_id' => $materia,
            'catedra_id' => Catedra::factory()->state([
                'materia_id' => $materia,
            ]),
            'fecha_inscripcion' => fake()->dateTimeBetween('-1 year', 'now'),
            'estado' => fake()->randomElement(['pendiente', 'confirmada', 'baja']),
        ];
    }
}
