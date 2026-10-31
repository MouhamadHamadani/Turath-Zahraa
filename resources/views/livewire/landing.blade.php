<div>
  {{-- Hero Section --}}
  <div class="relative bg-gray-100 min-h-screen h-full flex items-center justify-center">
    <div class="absolute inset-0">
      <img class="w-full h-full object-cover" src="{{ asset('images/hero.png') }}" alt="" role="presentation">
    </div>
    <div class="relative min-h-full max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 text-center">
      <div class="lg:w-44 lg:h-44 sm:w-32 sm:h-32 w-20 h-20 mx-auto mb-6 rounded-full bg-white"
        style="box-shadow: 0 0 15px 15px #ffffff">
        <img src="{{ asset('images/logo_2_bg.png') }}" alt="شعار المؤسسة" class="w-full h-full object-contain">
      </div>
      <h1 class="md:text-2xl text-xl font-extrabold text-gold lg:text-4xl">{{ $general->hero_title }}</h1>
      <p class="mt-6 lg:text-lg sm:text-base text-sm text-main-text max-w-3xl mx-auto">{{ $general->hero_subtitle }}</p>
      <div class="mt-5 flex sm:flex-row flex-col sm:gap-4 justify-center">
        <a href="{{ route('about-us') }}" wire:navigate
          class="mt-8 inline-block bg-linear-to-r from-ember to-gold text-button-text font-extrabold px-5 py-3 rounded-lg text-base hover:bg-linear-to-tr duration-300">تعرّف
          على المؤسسة</a>
        <a href="{{ route('archive.audio') }}" wire:navigate
          class="sm:mt-8 mt-4 inline-block text-gold border-2 border-gold hover:bg-gold hover:text-ember px-5 py-3 rounded-lg text-base font-bold duration-300">استكشاف
          الأرشيف</a>
      </div>
      @if ($followers)
        <ul class="text-second-text mt-6 text-sm max-w-3xl mx-auto list-disc marker:text-gold list-inside space-y-2">
          <li>أكثر من {{ number_format($followers) }} متابع على مواقع التواصل الاجتماعي</li>
        </ul>
      @endif
    </div>
  </div>

  {{-- About Us (Short Version) --}}
  <div id="about" class="w-full mx-auto py-16 px-11 sm:py-12 sm:px-6 lg:px-28 bg-brand-third">
    <h2 class="text-sm text-center font-extrabold text-ember">من نحن</h2>
    <p class="mt-4 text-lg text-gray-600 text-center max-w-5xl mx-auto">{{ $general->about_summary }}</p>
    <div class="mt-8 text-center">
      <a href="{{ route('about-us') }}" wire:navigate
        class="text-ember text-sm hover:text-gold duration-300 mx-auto">اقرأ المزيد <i
          class="fas fa-arrow-left ms-2" aria-hidden="true"></i></a>
    </div>
  </div>

  {{-- Widgets --}}
  <div id="explore" class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-brand-text text-center mb-3">استكشف أقسام الموقع</h2>
    <h3 class="text-sm text-gold text-center">الكتب والإصدارات، الأرشيف الصوتي والمرئي، الصور والوثائق، والبرامج — في
      مكان واحد</h3>
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach ($widgets as $widget)
        <div
          class="bg-brand-third shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 flex justify-between items-center sm:block">
          <div class="sm:block flex items-center gap-4">
            <div class="w-16 h-16 rounded-md bg-ember/10 flex items-center justify-center">
              <i class="{{ $widget['icon'] }} text-2xl text-ember" aria-hidden="true"></i>
            </div>
            <div>
              <h3 class="md:text-xl text-sm font-semibold text-gray-800 mt-4">{{ $widget['title'] }}</h3>
              <p class="md:mt-4 mt-2 text-gray-600 md:text-base text-xs">{{ $widget['description'] }}</p>
              <p class="mt-2 md:text-sm text-xs text-gray-500">عدد العناصر: {{ number_format($widget['value']) }}</p>
            </div>
          </div>
          <a href="{{ $widget['url'] }}" wire:navigate class="mt-4 inline-block text-brand font-medium hover:underline"
            aria-label="عرض المزيد: {{ $widget['title'] }}">
            <span class="sm:inline-block hidden">عرض المزيد</span>
            <i class="fas fa-arrow-left ms-2" aria-hidden="true"></i>
          </a>
        </div>
      @endforeach
    </div>
  </div>

  {{-- أحدث الأخبار والفعاليات --}}
  <div class="mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 bg-brand-third">
    <div class="flex justify-between items-center mb-8">
      <h2 class="md:text-2xl text-lg font-extrabold text-brand-text">أحدث الأخبار والفعاليات</h2>
      <a href="{{ route('news-events') }}" wire:navigate
        class="text-ember text-sm hover:text-gold duration-300">عرض الكل <i class="fas fa-arrow-left ms-2" aria-hidden="true"></i></a>
    </div>
    @if ($latest->isEmpty())
      <x-empty-state icon="fa-regular fa-newspaper">لا توجد أخبار أو فعاليات منشورة حتى الآن.</x-empty-state>
    @else
      <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($latest as $item)
          <div wire:key="latest-{{ $loop->index }}">
            <div
              class="relative bg-linear-120 from-[#e7ddc4] to-[#f3ede0] shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
              <x-event-badge :status="$item['status']" />
              @if ($item['image'])
                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" class="w-full h-48 object-cover rounded-md">
              @else
                <div class="w-full h-48 rounded-md flex items-center justify-center bg-main-bg/50" aria-hidden="true">
                  <i class="fa-regular fa-calendar-days text-4xl text-gold/60"></i>
                </div>
              @endif
            </div>
            <h3 class="text-base font-semibold text-gray-800 mt-4">{{ $item['title'] }}</h3>
            <p class="mt-2 text-sm text-gray-500"><i class="fa-regular fa-calendar me-2" aria-hidden="true"></i>{{ $item['date']->translatedFormat('j F Y') }}</p>
            <a href="{{ route('news-events') }}" wire:navigate class="mt-4 inline-block text-brand font-medium hover:underline">
              عرض المزيد
              <i class="fas fa-arrow-left ms-2" aria-hidden="true"></i>
            </a>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
