@props(['class' => 'h-20 w-auto'])

<img
    src="{{ asset('logo.png') }}"
    alt="FCE::Lucy"
    {{ $attributes->merge(['class' => $class]) }}
>

