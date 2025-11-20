<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'alumno_id',
        'materia_id',
        'catedra_id',
        'fecha_inscripcion',
        'estado',
        'moodle_payload',
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function catedra(): BelongsTo
    {
        return $this->belongsTo(Catedra::class);
    }
}
