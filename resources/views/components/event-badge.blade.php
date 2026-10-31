@props(['status'])

@php
    $label = ['upcoming' => 'فعالية قادمة', 'ongoing' => 'جارية الآن', 'past' => 'فعالية منتهية', 'news' => 'خبر'][$status] ?? null;
    $color = ['upcoming' => 'bg-gold text-brand-text', 'ongoing' => 'bg-ember text-white', 'past' => 'bg-[#c9c1ac] text-brand-text', 'news' => 'bg-[#2f4a37] text-white'][$status] ?? '';
@endphp

@if ($label)
  <span {{ $attributes->class(["absolute start-3 top-3 rounded-full px-3 py-1 text-xs font-semibold", $color]) }}>{{ $label }}</span>
@endif
