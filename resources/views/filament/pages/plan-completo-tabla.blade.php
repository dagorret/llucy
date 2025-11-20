<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Plan completo (tabla)</h1>
                <p class="text-sm text-gray-600">Una fila por combinación Plan / Carrera / Materia / Cátedra.</p>
            </div>
        </div>

        <div class="overflow-auto shadow-sm ring-1 ring-gray-200 rounded-xl">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Plan</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Carrera</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Materia</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Cátedra</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Modalidad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($planes as $plan)
                        @foreach ($plan->carreras as $carrera)
                            @foreach ($carrera->materias as $materia)
                                @php
                                    $catedras = $materia->catedras;
                                    $rows = max($catedras->count(), 1);
                                @endphp
                                @if ($catedras->isEmpty())
                                    <tr>
                                        <td class="px-4 py-2 align-top">{{ $plan->codigo }} - {{ $plan->nombre }}</td>
                                        <td class="px-4 py-2 align-top">{{ $carrera->codigo }} - {{ $carrera->nombre }}</td>
                                        <td class="px-4 py-2 align-top">{{ $materia->codigo }} - {{ $materia->nombre }}</td>
                                        <td class="px-4 py-2 align-top text-gray-500" colspan="2">Sin cátedras</td>
                                    </tr>
                                @else
                                    @foreach ($catedras as $catedra)
                                        <tr>
                                            <td class="px-4 py-2 align-top">{{ $plan->codigo }} - {{ $plan->nombre }}</td>
                                            <td class="px-4 py-2 align-top">{{ $carrera->codigo }} - {{ $carrera->nombre }}</td>
                                            <td class="px-4 py-2 align-top">{{ $materia->codigo }} - {{ $materia->nombre }}</td>
                                            <td class="px-4 py-2 align-top">{{ $catedra->codigo }}</td>
                                            <td class="px-4 py-2 align-top capitalize">{{ $catedra->modalidad }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
