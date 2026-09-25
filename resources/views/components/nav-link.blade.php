@props(['route'])

<a href="{{ route($route) }}" {{ $attributes->class([
	'hover:text-brand duration-300 font-cairo-bold',
	'text-brand-second border-b' => request()->routeIs($route),
	'text-brand-text' => ! request()->routeIs($route),
]) }} wire:navigate>
    {{ $slot }}
</a>
