<div>
  <x-page-header title="الأخبار والفعاليات" description="تابع نشاط المؤسسة: فعاليات قادمة، وفعاليات سابقة، وآخر الأخبار." />

  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @foreach ([['الفعاليات القادمة', $upcoming, 'لا توجد فعاليات قادمة حاليًا.'], ['فعاليات سابقة', $past, 'لا توجد فعاليات سابقة بعد.']] as [$heading, $events, $empty])
      <section class="mb-14">
        <h2 class="mb-6 text-xl font-extrabold text-brand-text">{{ $heading }}</h2>
        @if ($events->isEmpty())
          <x-empty-state icon="fa-regular fa-calendar">{{ $empty }}</x-empty-state>
        @else
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($events as $event)
              <article class="flex flex-col overflow-hidden rounded-lg border border-brand-text/10 bg-brand-third shadow-sm" wire:key="{{ $heading }}-{{ $event->id }}">
                <div class="relative aspect-[16/9] bg-[#e7ddc4]/50">
                  @if ($url = $event->getFirstMediaUrl('cover'))
                    <img src="{{ $url }}" alt="{{ $event->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                  @else
                    <div class="flex h-full items-center justify-center" aria-hidden="true"><i class="fa-regular fa-calendar-days text-4xl text-gold/60"></i></div>
                  @endif
                  <x-event-badge :status="$event->timeline_status" />
                </div>
                <div class="flex flex-1 flex-col p-4">
                  <h3 class="text-base font-bold text-brand-text">{{ $event->title }}</h3>
                  <p class="mt-2 text-sm text-brand-second-text"><i class="fa-regular fa-calendar me-2" aria-hidden="true"></i>{{ $event->starts_at->translatedFormat('j F Y') }}</p>
                  <p class="mt-1 text-sm text-brand-second-text"><i class="fa-solid fa-location-dot me-2" aria-hidden="true"></i>{{ collect([$event->venue, $event->city])->filter()->implode('، ') }}</p>
                  @if ($event->registration_url && $event->timeline_status !== 'past')
                    <a href="{{ $event->registration_url }}" target="_blank" rel="noopener" class="mt-auto inline-block pt-4 text-sm font-medium text-brand hover:underline">
                      التسجيل في الفعالية <i class="fas fa-arrow-left ms-2" aria-hidden="true"></i>
                    </a>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        @endif
      </section>
    @endforeach

    <section>
      <h2 class="mb-6 text-xl font-extrabold text-brand-text">آخر الأخبار</h2>
      @if ($news->isEmpty())
        <x-empty-state icon="fa-regular fa-newspaper">لا توجد أخبار منشورة حتى الآن.</x-empty-state>
      @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($news as $article)
            <x-content-card :card="\App\Livewire\Support\ContentCard::make($article)" icon="fa-regular fa-newspaper" wire:key="news-{{ $article->id }}" />
          @endforeach
        </div>
        <div class="mt-10">{{ $news->links() }}</div>
      @endif
    </section>
  </div>
</div>
