<?php

use App\Models\Materia;
use App\Models\Plan;

it('crea un plan con materias relacionadas', function () {
    $plan = Plan::factory()
        ->has(Materia::factory()->count(3))
        ->create();

    expect($plan->materias)->toHaveCount(3);

    $plan->materias->each(function (Materia $materia) use ($plan) {
        expect($materia->plan_id)->toBe($plan->id);
    });
});

it('vincula una materia a un plan', function () {
    $materia = Materia::factory()->create();

    expect($materia->plan)->toBeInstanceOf(Plan::class);
    expect($materia->plan_id)->toBe($materia->plan->id);
});
