<div>
  <x-page-header title="تراث السيدة الزهراء (ع)"
    description="كتب وصوتيات ومرئيات وصور ووثائق مصنّفة بحسب موضوعات التراث الفاطمي." />

  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-10 flex flex-wrap items-center gap-2" role="group" aria-label="موضوعات التراث">
      <button type="button" wire:click="$set('topic', '')"
        @class([
            'rounded-full border px-4 py-1.5 text-sm font-bold duration-200',
            'border-ember bg-ember text-white' => ! $selected,
            'border-brand-text/15 text-brand-text hover:border-gold hover:text-brand' => $selected,
        ])>الكل</button>
      @foreach ($topics as $topic)
        <button type="button" wire:click="$set('topic', '{{ $topic->id }}')" wire:key="topic-{{ $topic->id }}"
          @class([
              'rounded-full border px-4 py-1.5 text-sm font-bold duration-200',
              'border-ember bg-ember text-white' => $selected?->is($topic),
              'border-brand-text/15 text-brand-text hover:border-gold hover:text-brand' => ! $selected?->is($topic),
          ])>{{ $topic->name }}</button>
      @endforeach
    </div>

    @forelse ($sections as $section)
      <section class="mb-14" wire:key="section-{{ $loop->index }}">
        <div class="mb-6 flex items-center justify-between gap-4">
          <h2 class="flex items-center gap-3 text-xl font-extrabold text-brand-text">
            <i class="{{ $section['icon'] }} text-gold" aria-hidden="true"></i>
            {{ $section['label'] }}
          </h2>
          <a href="{{ route($section['route']) }}" wire:navigate class="text-sm text-ember duration-300 hover:text-gold">
            عرض الكل <i class="fas fa-arrow-left ms-2" aria-hidden="true"></i>
          </a>
        </div>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          @foreach ($section['items'] as $card)
            <x-content-card :card="$card" :icon="$section['icon']" wire:key="heritage-{{ $loop->parent->index }}-{{ $loop->index }}" />
          @endforeach
        </div>
      </section>
    @empty
      <x-empty-state icon="fa-solid fa-feather-pointed">
        {{ $selected ? "لا توجد مواد منشورة في موضوع «{$selected->name}» حتى الآن." : 'لم تُنشر مواد التراث بعد. ترقّبوا إضافتها قريبًا.' }}
      </x-empty-state>
    @endforelse
  </div>
</div>
