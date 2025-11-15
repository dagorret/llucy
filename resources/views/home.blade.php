{{-- resources/views/home.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inicio
        </h2>
    </x-slot>

    <div class="p-6">
        {{-- Texto --}}
        <p class="mb-4 text-gray-700">
            Bienvenido, estás logueado en Lucy.
        </p>

        {{-- Botones --}}
        <div class="flex gap-3">
            {{-- Botón Perfil --}}
            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded">
                Perfil
            </a>

            {{-- Botón Dashboard (Filament) --}}
            <a href="{{ url('/dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded">
                Dashboard
            </a>
        </div>
    </div>
</x-app-layout>

