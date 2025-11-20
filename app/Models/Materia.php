<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'nombre',
        'codigo',
        'codigo_uti',
        'cuatrimestre',
    ];

    protected $casts = [
        'cuatrimestre' => 'integer',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
