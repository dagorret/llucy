<?php

namespace App\Filament\Pages;

use App\Models\Plan;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use UnitEnum;

class PlanCompletoTabla extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected string $view = 'filament.pages.plan-completo-tabla';

    protected static ?string $navigationLabel = 'Plan completo (tabla)';

    protected static UnitEnum|string|null $navigationGroup = 'Plan Completo';

    protected static ?int $navigationSort = 1;

    protected function getViewData(): array
    {
        $planes = Plan::with(['carreras.materias.catedras'])
            ->orderByDesc('fecha_desde')
            ->get();

        return compact('planes');
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return 'plan-completo/tabla';
    }
}
