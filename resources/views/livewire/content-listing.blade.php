<div>
  <x-page-header :title="$heading" :description="$description">
    <div class="mx-auto mt-6 max-w-xl">
      <label for="listing-search" class="sr-only">بحث بالعنوان</label>
      <div class="relative">
        <i class="fas fa-search pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 text-brand-second-text" aria-hidden="true"></i>
        <input id="listing-search" type="search" wire:model.live.debounce.300ms="search" placeholder="ابحث بالعنوان..."
          class="w-full rounded-full border border-brand-text/15 bg-main-bg py-3 ps-11 pe-4 text-sm text-brand-text focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
      </div>
    </div>
  </x-page-header>

  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @if ($filter && count($filter['options']))
      <div class="mb-8 flex flex-wrap items-center gap-2" role="group" aria-label="{{ $filter['label'] }}">
        <button type="button" wire:click="$set('{{ $filter['property'] }}', '')"
          @class([
              'rounded-full border px-4 py-1.5 text-sm font-bold duration-200',
              'border-ember bg-ember text-white' => $this->{$filter['property']} === '',
              'border-brand-text/15 text-brand-text hover:border-gold hover:text-brand' => $this->{$filter['property']} !== '',
          ])>الكل</button>
        @foreach ($filter['options'] as $value => $label)
          <button type="button" wire:click="$set('{{ $filter['property'] }}', '{{ $value }}')" wire:key="filter-{{ $value }}"
            @class([
                'rounded-full border px-4 py-1.5 text-sm font-bold duration-200',
                'border-ember bg-ember text-white' => (string) $this->{$filter['property']} === (string) $value,
                'border-brand-text/15 text-brand-text hover:border-gold hover:text-brand' => (string) $this->{$filter['property']} !== (string) $value,
            ])>{{ $label }}</button>
        @endforeach
      </div>
    @endif

    @if ($cards->isEmpty())
      <x-empty-state :icon="$icon">
        {{ $search !== '' || $category !== '' ? 'لا توجد نتائج مطابقة لبحثك. جرّب كلمات أخرى أو أزل التصفية.' : 'لا يوجد محتوى منشور في هذا القسم حتى الآن.' }}
      </x-empty-state>
    @else
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($cards as $card)
          <x-content-card :card="$card" :icon="$icon" wire:key="card-{{ $loop->index }}-{{ $cards->currentPage() }}" />
        @endforeach
      </div>

      <div class="mt-10">{{ $cards->links() }}</div>
    @endif
  </div>
</div>
