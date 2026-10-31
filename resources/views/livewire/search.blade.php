<div>
  <x-page-header title="البحث" description="ابحث في الكتب والبحوث والمطبوعات والأرشيف والأخبار.">
    <form x-on:submit.prevent class="mx-auto mt-6 max-w-xl" role="search">
      <label for="site-search" class="sr-only">كلمة البحث</label>
      <div class="relative">
        <i class="fas fa-search pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 text-brand-second-text" aria-hidden="true"></i>
        <input id="site-search" type="search" wire:model.live.debounce.400ms="q" placeholder="اكتب كلمتين على الأقل..." autofocus
          class="w-full rounded-full border border-brand-text/15 bg-main-bg py-3 ps-11 pe-4 text-sm text-brand-text focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
      </div>
    </form>
  </x-page-header>

  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @if (! $searchable)
      <x-empty-state icon="fa-solid fa-magnifying-glass">
        {{ $term === '' ? 'اكتب كلمة في خانة البحث لعرض النتائج.' : 'اكتب حرفين على الأقل لبدء البحث.' }}
      </x-empty-state>
    @elseif ($groups->isEmpty())
      <x-empty-state icon="fa-solid fa-magnifying-glass">لا توجد نتائج لـ «{{ $term }}». جرّب كلمات أخرى.</x-empty-state>
    @else
      @foreach ($groups as $group)
        <section class="mb-12" wire:key="group-{{ $loop->index }}">
          <div class="mb-6 flex items-center justify-between gap-4">
            <h2 class="flex items-center gap-3 text-xl font-extrabold text-brand-text">
              <i class="{{ $group['icon'] }} text-gold" aria-hidden="true"></i>{{ $group['label'] }}
            </h2>
            @if ($group['route'] !== 'news-events')
              <a href="{{ route($group['route'], ['q' => $term]) }}" wire:navigate class="text-sm text-ember duration-300 hover:text-gold">
                عرض الكل <i class="fas fa-arrow-left ms-2" aria-hidden="true"></i>
              </a>
            @endif
          </div>
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($group['items'] as $card)
              <x-content-card :card="$card" :icon="$group['icon']" wire:key="result-{{ $loop->parent->index }}-{{ $loop->index }}" />
            @endforeach
          </div>
        </section>
      @endforeach
    @endif
  </div>
</div>
