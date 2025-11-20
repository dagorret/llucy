<x-filament-panels::page>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Plan completo (árbol)</h1>
            <p class="text-sm text-gray-600">Planes con sus carreras, materias y cátedras agrupadas.</p>
        </div>

        <div class="space-y-4">
            @foreach ($planes as $plan)
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-4 py-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-800">{{ $plan->codigo }}</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $plan->nombre }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Desde: {{ optional($plan->fecha_desde)->format('Y-m-d') }}</div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($plan->carreras as $carrera)
                            <div class="px-4 py-3">
                                <div class="font-semibold text-gray-800">{{ $carrera->codigo }} — {{ $carrera->nombre }}</div>
                                <div class="mt-2 space-y-2">
                                    @forelse ($carrera->materias as $materia)
                                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-3 py-2">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="text-sm font-semibold text-gray-800">{{ $materia->codigo }}</span>
                                                <span class="text-sm text-gray-700">{{ $materia->nombre }}</span>
                                                <span class="text-xs text-gray-500">Cuatrimestre: {{ $materia->cuatrimestre }}</span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-700">
                                                Cátedras:
                                                @if ($materia->catedras->isEmpty())
                                                    <span class="text-gray-500">Sin cátedras</span>
                                                @else
                                                    {{ $materia->catedras->map(fn ($c) => $c->codigo . ' (' . $c->modalidad . ')')->join(', ') }}
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-sm text-gray-500">Sin materias.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
