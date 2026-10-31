<nav x-data="{ open: false }" @keydown.escape.window="open = false" x-on:livewire:navigated.window="open = false" class="relative"
  aria-label="القائمة الرئيسية">
  <div class="flex items-center justify-between py-6 px-10 h-24 bg-main-bg text-brand-text">
    <div class="text-lg font-semibold flex">
      <x-logo />
      <p class="max-w-40 lg:block hidden">{{ app(\App\Settings\GeneralSettings::class)->site_name }}</p>
    </div>
    <div class="lg:flex hidden gap-7">
      @foreach ($items as $item)
        @if (isset($item['children']))
          <x-nav-dropdown :item="$item" />
        @else
          <x-nav-link :href="$item['url']" :active="$item['active']">{{ $item['label'] }}</x-nav-link>
        @endif
      @endforeach
    </div>
    <div class="flex items-center gap-4">
      <a href="{{ route('search') }}" wire:navigate aria-label="بحث"
        class="hover:text-brand bg-[#f3ede0] p-2 rounded-full duration-300 w-9 h-9 flex items-center justify-center">
        <i class="fas fa-search" aria-hidden="true"></i>
      </a>
      <div class="lg:hidden">
        <button type="button" id="menu-toggle" class="text-brand-text bg-[#f3ede0] p-2 pb-1 rounded-md focus:outline-none"
          @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-menu"
          :aria-label="open ? 'إغلاق القائمة' : 'فتح القائمة'" aria-label="فتح القائمة">
          <i class="fas text-xl" :class="open ? 'fa-xmark' : 'fa-bars'" aria-hidden="true"></i>
        </button>
      </div>
    </div>
  </div>
  <div id="mobile-menu" class="lg:hidden relative bg-main-bg" x-show="open" x-cloak>
    <div
      class="px-5 pt-2 pb-5 bg-main-bg text-brand-text flex flex-col gap-4 absolute w-full z-50 max-h-[calc(100dvh-6rem)] overflow-y-auto shadow-lg">
      @foreach ($items as $item)
        @if (isset($item['children']))
          <x-nav-dropdown :item="$item" :mobile="true" />
        @else
          <x-nav-link :href="$item['url']" :active="$item['active']" @click="open = false">{{ $item['label'] }}</x-nav-link>
        @endif
      @endforeach
    </div>
  </div>
</nav>
