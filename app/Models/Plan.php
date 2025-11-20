<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = [
        'codigo',
        'nombre',
        'fecha_desde',
    ];

    protected $casts = [
        'fecha_desde' => 'date',
    ];

    public function carreras(): HasMany
    {
        return $this->hasMany(Carrera::class);
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class);
    }
}
