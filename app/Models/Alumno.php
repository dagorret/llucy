<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_documento',
        'dni',
        'nombre',
        'apellido',
        'email_personal',
        'fecha_nacimiento',
        'cohorte',
        'localidad',
        'telefono',
        'email_institucional',
        'estado_actual',
        'fecha_ingreso',
        'estado_ingreso',
        'teams_password',
        'teams_payload',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
    ];

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }
}
