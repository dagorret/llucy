<?php

namespace App\Filament\Pages;

use App\Models\Plan;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use UnitEnum;

class PlanCompletoArbol extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-list-bullet';

    protected string $view = 'filament.pages.plan-completo-arbol';

    protected static ?string $navigationLabel = 'Plan completo (árbol)';

    protected static UnitEnum|string|null $navigationGroup = 'Plan Completo';

    protected static ?int $navigationSort = 2;

    protected function getViewData(): array
    {
        $planes = Plan::with(['carreras.materias.catedras'])
            ->orderByDesc('fecha_desde')
            ->get();

        return compact('planes');
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return 'plan-completo/arbol';
    }
}
