<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'fecha_desde',
    ];

    protected $casts = [
        'fecha_desde' => 'date',
    ];

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class);
    }
}
