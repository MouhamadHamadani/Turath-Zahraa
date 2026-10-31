<div>
  {{-- Hero --}}
  <div class="relative bg-gray-50">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 relative z-10 text-center">
      <div class="text-center">
        <h1 class="text-2xl font-extrabold text-gold sm:text-3xl sm:tracking-tight lg:text-4xl">عن المؤسسة</h1>
        <p class="mt-6 max-w-3xl mx-auto text-base text-main-text">نبذة عن مؤسسة إحياء تراث الصديقة الشهيدة (ع)، رؤيتها
          ورسالتها وأهدافها في خدمة التراث الفاطمي</p>
      </div>
    </div>
    <div class="absolute inset-0">
      <img class="w-full h-full object-cover" src="{{ asset('images/hero.png') }}" alt="" role="presentation">
    </div>
  </div>

  {{-- Who we are --}}
  <div class="bg-brand-third">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
        <div class="lg:w-1/2">
          <h2 class="text-sm font-extrabold text-ember mb-2">من نحن</h2>
          <h3 class="text-2xl text-brand-text font-extrabold">مؤسسة بحثية تراثية، غير ربحية، مقرّها {{ $general->headquarters }}</h3>
          <p class="mt-4 text-base text-[#4b4436]">{{ $general->about_summary }}</p>
          @if ($info)
            <div class="mt-5 flex flex-wrap gap-4">
              @foreach ($info as $item)
                <div class="bg-main-bg rounded-xl px-5 py-3">
                  <p class="text-base font-extrabold text-ember">{{ $item['value'] }}</p>
                  <span class="text-sm text-[#8a8272]">{{ $item['title'] }}</span>
                </div>
              @endforeach
            </div>
          @endif
        </div>
        <div class="lg:w-1/2">
          <img src="{{ asset('images/about-us/who-we-are.jpg') }}" alt="من نحن" class="rounded-lg shadow-lg">
        </div>
      </div>
    </div>
  </div>

  {{-- Our vision/mission --}}
  @if ($vision || $mission)
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
        @if ($vision)
          <div class="bg-brand-third rounded-2xl p-8 shadow-lg mb-8 md:mb-0 border border-dashed border-brand-text/10">
            <div class="flex items-center gap-4 mb-4 rounded-xl p-4 bg-gold/10 w-fit">
              <i class="fas fa-eye text-gold text-2xl" aria-hidden="true"></i>
            </div>
            <h2 class="text-xl font-extrabold text-brand-text mb-4">الرؤية</h2>
            <div class="rich-content text-sm text-brand-second-text">{{ $vision }}</div>
          </div>
        @endif
        @if ($mission)
          <div class="bg-brand-third rounded-2xl p-8 shadow-lg mb-8 md:mb-0 border border-brand-text/10">
            <div class="flex items-center gap-4 mb-4 rounded-xl p-4 bg-ember/10 w-fit">
              <i class="fas fa-message text-ember text-2xl" aria-hidden="true"></i>
            </div>
            <h2 class="text-xl font-extrabold text-brand-text mb-4">الرسالة</h2>
            <div class="rich-content text-sm text-brand-second-text">{{ $mission }}</div>
          </div>
        @endif
      </div>
    </div>
  @endif

  {{-- Our Goals --}}
  @if ($general->goals)
    <div class="bg-brand-third">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h2 class="text-center text-2xl font-extrabold text-brand-text mb-2">أهداف المؤسسة</h2>
        <p class="text-center text-sm text-brand-second-text">غايات تحكم عمل المؤسسة في خدمة التراث الفاطمي</p>

        <div class="mt-8 grid md:grid-cols-3 grid-cols-1 gap-5">
          @foreach ($general->goals as $i => $goal)
            <div class="bg-main-bg rounded-xl p-6 shadow-lg border border-brand-text/10 flex items-center gap-3">
              <div class="flex items-center gap-4 rounded-xl p-4 bg-ember/10 w-fit">
                <span class="text-ember text-2xl">{{ $i + 1 }}</span>
              </div>
              <div>
                <h3 class="text-lg font-extrabold text-brand-text mb-2">{{ $goal['title'] }}</h3>
                <p class="text-sm text-brand-second-text">{{ $goal['description'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  {{-- Institution timeline --}}
  @if ($timeline->isNotEmpty())
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-extrabold text-gray-900 mb-8 text-center">مسيرة المؤسسة</h2>
      <div class="relative">
        <div class="border-s-2 border-dashed border-brand-text/10 absolute h-full start-1/2"></div>
        <div class="space-y-12">
          @foreach ($timeline as $milestone)
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8 relative" wire:key="milestone-{{ $milestone->id }}">
              <div class="lg:w-1/2 order-last lg:order-first w-full">
                @if ($image = $milestone->getFirstMediaUrl('image'))
                  <img src="{{ $image }}" alt="{{ $milestone->title }}" loading="lazy" class="rounded-lg shadow-lg w-full h-64 object-cover">
                @else
                  <div class="rounded-lg shadow-lg w-full h-64 bg-[#e7ddc4]/50 flex items-center justify-center" aria-hidden="true">
                    <i class="fa-solid fa-landmark text-5xl text-gold/60"></i>
                  </div>
                @endif
              </div>
              <div class="lg:w-1/2 bg-brand-text/5 p-6 rounded-lg shadow-lg relative z-10 w-full">
                <h3 class="text-sm font-bold text-gray-900">{{ $milestone->title }}</h3>
                <p class="mt-2 text-gray-600 text-sm">{{ $milestone->description }}</p>
                <span class="absolute -top-4 start-4 bg-ember text-white px-3 py-1 rounded-full text-sm">{{ $milestone->year }}</span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
</div>
