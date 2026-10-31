@props(['route' => null, 'params' => [], 'href' => null, 'active' => null])

@php
    $url = $href ?? route($route, $params);
    $isActive = $active ?? ($route && request()->routeIs($route));
@endphp

<a href="{{ $url }}" {{ $attributes->class([
    'hover:text-brand duration-300 font-bold',
    'text-brand-second border-b' => $isActive,
    'text-brand-text' => ! $isActive,
]) }} @if ($isActive) aria-current="page" @endif wire:navigate>
    {{ $slot }}
</a>
