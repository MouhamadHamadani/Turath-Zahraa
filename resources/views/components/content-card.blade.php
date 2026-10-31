@props(['card', 'icon' => 'fa-solid fa-folder-open'])

<article {{ $attributes->class('flex flex-col overflow-hidden rounded-lg border border-brand-text/10 bg-brand-third shadow-sm duration-300 hover:shadow-md') }}>
  <div class="relative aspect-[4/3] bg-[#e7ddc4]/50">
    @if (! empty($card['image']))
      <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
    @else
      <div class="flex h-full w-full items-center justify-center" aria-hidden="true">
        <i class="{{ $icon }} text-4xl text-gold/60"></i>
      </div>
    @endif
    @if (! empty($card['badge']))
      <span class="absolute start-3 top-3 rounded-full bg-gold px-3 py-1 text-xs font-semibold text-brand-text">{{ $card['badge'] }}</span>
    @endif
  </div>
  <div class="flex flex-1 flex-col p-4">
    <h3 class="text-base font-bold text-brand-text">{{ $card['title'] }}</h3>
    @if (! empty($card['subtitle']))
      <p class="mt-1 text-sm text-brand-second-text">{{ $card['subtitle'] }}</p>
    @endif
    @if (! empty($card['meta']))
      <p class="mt-2 text-xs text-brand-second-text">{{ implode(' • ', array_filter($card['meta'])) }}</p>
    @endif
    @if (! empty($card['action']))
      <a href="{{ $card['action']['url'] }}" target="_blank" rel="noopener"
        class="mt-auto inline-block pt-4 text-sm font-medium text-brand hover:underline">
        {{ $card['action']['label'] }}
        <i class="fas fa-arrow-left ms-2" aria-hidden="true"></i>
      </a>
    @endif
  </div>
</article>
