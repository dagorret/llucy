<?php

namespace Database\Factories;

use App\Models\Alumno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alumno>
 */
class AlumnoFactory extends Factory
{
    protected $model = Alumno::class;

    public function definition(): array
    {
        return [
            'tipo_documento' => 'dni',
            'dni' => fake()->unique()->numerify('########'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'email_personal' => fake()->safeEmail(),
            'fecha_nacimiento' => fake()->dateTimeBetween('-35 years', '-18 years')->format('Y-m-d'),
            'cohorte' => (int) fake()->year(),
            'localidad' => fake()->city(),
            'telefono' => fake()->phoneNumber(),
            'email_institucional' => fake()->optional()->companyEmail(),
            'estado_actual' => fake()->randomElement(['preinscripto', 'aspirante', 'ingresante', 'alumno']),
            'fecha_ingreso' => fake()->optional()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'estado_ingreso' => fake()->optional()->randomElement(['regular', 'readmision', 'condicional']),
        ];
    }
}
