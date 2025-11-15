<x-app-layout>
    {{-- Si no querés el header clásico de Breeze, lo podés borrar --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inicio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl p-8">
                <div class="flex flex-col items-center justify-center text-center space-y-4">
                    {{-- Logo centrado --}}
                    <x-application-logo class="h-24 w-auto" />

                    {{-- Texto FCE::Lucy --}}
                    <h1 class="text-3xl font-bold text-gray-800">
                        FCE::Lucy
                    </h1>

                    <p class="text-gray-600">
                        Bienvenido, estás logueado en Lucy.
                    </p>

                    {{-- Botones, links, etc. opcionales --}}
                    <div class="mt-4 flex gap-4">
                        <a href="{{ route('profile.edit') }}"
                           class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            Perfil
                        </a>

                        <a href="{{ url('/dashboard') }}" {{-- ajustá esta ruta al panel de Filament --}}
                           class="px-4 py-2 text-sm font-semibold rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">
                            Ir al panel de administración
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

