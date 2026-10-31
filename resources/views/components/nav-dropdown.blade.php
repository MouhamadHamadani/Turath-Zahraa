@props(['item', 'mobile' => false])

@php
    $id = 'nav-menu-'.($mobile ? 'm-' : 'd-').md5($item['label']);
@endphp

@if ($mobile)
  {{-- Mobile: accordion --}}
  <div x-data="{ expanded: {{ $item['active'] ? 'true' : 'false' }} }">
    <button type="button" @click="expanded = !expanded" :aria-expanded="expanded.toString()" aria-controls="{{ $id }}"
      @class([
          'flex w-full items-center justify-between gap-2 font-bold duration-300 hover:text-brand',
          'text-brand-second' => $item['active'],
          'text-brand-text' => ! $item['active'],
      ])>
      <span>{{ $item['label'] }}</span>
      <i class="fas fa-chevron-down text-xs duration-200" :class="{ 'rotate-180': expanded }" aria-hidden="true"></i>
    </button>
    <ul id="{{ $id }}" x-show="expanded" x-cloak x-collapse class="mt-2 flex flex-col gap-2 border-s-2 border-gold/40 ps-4">
      @foreach ($item['children'] as $child)
        <li>
          <x-nav-link :href="$child['url']" :active="$child['active']" class="block py-1 text-sm" @click="open = false">
            {{ $child['label'] }}
          </x-nav-link>
        </li>
      @endforeach
    </ul>
  </div>
@else
  {{-- Desktop: opens on click, and on hover for pointer devices --}}
  <div class="relative"
    x-data="{
      open: false,
      hoverOpened: false,
      timer: null,
      canHover() { return window.matchMedia('(hover: hover) and (min-width: 1024px)').matches },
      show() { clearTimeout(this.timer); this.open = true },
      hide() { clearTimeout(this.timer); this.timer = setTimeout(() => this.open = false, 150) },
      toggle() {
        if (this.canHover() && this.hoverOpened) { this.hoverOpened = false; return }
        this.open = !this.open
      },
      close(focusTrigger = false) { this.open = false; this.hoverOpened = false; if (focusTrigger) this.$refs.trigger.focus() },
      links() { return Array.from(this.$refs.menu.querySelectorAll('[role=menuitem]')) },
      focusFirst() { this.open = true; setTimeout(() => this.links()[0]?.focus(), 50) },
      move(step) {
        const links = this.links()
        const next = (links.indexOf(document.activeElement) + step + links.length) % links.length
        links[next]?.focus()
      },
    }"
    @mouseenter="if (canHover()) { hoverOpened = !open; show() }"
    @mouseleave="if (canHover()) hide()"
    @keydown.escape="close(true)"
    @focusout="if (!$el.contains($event.relatedTarget)) close()"
    @click.outside="close()"
    x-on:livewire:navigated.window="close()">
    <button type="button" x-ref="trigger" @click="toggle()" @keydown.arrow-down.prevent="focusFirst()"
      aria-haspopup="menu" :aria-expanded="open.toString()" aria-controls="{{ $id }}"
      @class([
          'flex items-center gap-2 font-bold duration-300 hover:text-brand',
          'text-brand-second border-b' => $item['active'],
          'text-brand-text' => ! $item['active'],
      ])>
      <span>{{ $item['label'] }}</span>
      <i class="fas fa-chevron-down text-xs duration-200" :class="{ 'rotate-180': open }" aria-hidden="true"></i>
    </button>
    <div id="{{ $id }}" x-ref="menu" x-show="open" x-cloak role="menu" aria-label="{{ $item['label'] }}"
      @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)"
      x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1"
      x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
      class="absolute start-0 top-full z-50 min-w-48 pt-3">
      <div class="overflow-hidden rounded-lg border border-brand-text/10 bg-main-bg py-1 shadow-lg">
        @foreach ($item['children'] as $child)
          <a href="{{ $child['url'] }}" role="menuitem" wire:navigate @click="close()"
            @if ($child['active']) aria-current="page" @endif
            @class([
                'block px-4 py-2 text-sm font-bold duration-200 hover:bg-brand-third hover:text-brand focus:bg-brand-third focus:text-brand focus:outline-none',
                'text-brand-second' => $child['active'],
                'text-brand-text' => ! $child['active'],
            ])>
            {{ $child['label'] }}
          </a>
        @endforeach
      </div>
    </div>
  </div>
@endif
