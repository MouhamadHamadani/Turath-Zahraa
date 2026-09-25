<div>
  {{-- Hero Section --}}
  <div class="relative bg-gray-100 h-screen flex items-center justify-center">
    <div class="absolute inset-0">
      <img class="w-full h-full object-cover" src="{{ asset('images/hero.png') }}" alt="Hero Image">
      {{-- <div class="absolute inset-0 bg-gray-900 opacity-50"></div> --}}
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 text-center">
      <div class="w-44 h-44 mx-auto mb-6 rounded-full bg-white" style="box-shadow: 0 0 15px 15px #ffffff">
        <img src="{{ asset('images/logo_2_bg.png') }}" alt="logo" class="w-full h-full object-contain">
      </div>
      <h1 class="text-2xl font-extrabold text-gold sm:text-3xl lg:text-4xl">مؤسسة إحياء تراث الصديقة الشهيدة عليها
        السلام
      </h1>
      <p class="mt-6 text-lg text-main-text max-w-3xl">
        نجمع تراث الصديقة الزهراء عليها السلام ونوثّقه ونؤرشفه وننشره، خدمةً للباحثين والحوزات والجامعات والمهتمين في كل
        مكان.
      </p>
      <div class="mt-5 flex space-x-4 justify-center">
        <a href="{{ route('about-us') }}" wire:navigate
          class="mt-8 inline-block bg-linear-to-r from-ember to-gold text-button-text font-extrabold px-5 py-3 rounded-lg text-base hover:bg-linear-to-tr duration-300">تعرّف
          على المؤسسة</a>
        <a href="#explore"
          class="mt-8 inline-block text-gold border-2 border-gold hover:bg-gold hover:text-ember px-5 py-3 rounded-lg text-base font-bold duration-300">استكشاف
          الأرشيف</a>
      </div>
      <ul class="text-second-text mt-6 text-sm max-w-3xl mx-auto list-disc  marker:text-gold list-inside space-y-2">
        <li>أكثر من 15,000 متابع على مواقع التواصل الاجتماعي</li>

      </ul>
    </div>
  </div>

  {{-- About Us (Short Version) --}}
  <div id="about" class="w-full mx-auto py-16 px-11 sm:py-12 sm:px-6 lg:px-28 bg-brand-third">
    <h2 class="text-sm text-center font-extrabold text-ember">من نحن</h2>
    <p class="mt-4 text-lg text-gray-600 text-center max-w-5xl mx-auto">
      مؤسسة إحياء تراث الصديقة الشهيدة (ع) مؤسسة بحثية وتعليمية غير ربحية، مقرها النجف الأشرف في العراق، تُعنى بجمع
      التراث الفاطمي وتوثيقه وأرشفته، ودعم الدراسات والبحوث المتعلقة به، وإيصاله إلى الباحثين والأجيال الجديدة في أنحاء
      العالم العربي.
    </p>
    <div class="mt-8 text-center">
      <a href="{{ route('about-us') }}" wire:navigate
        class="text-ember text-sm hover:text-gold duration-300 mx-auto">إقرأ المزيد <i
          class="fas fa-arrow-left mr-2"></i></a>
    </div>
  </div>

  {{-- Widgets --}}
  <div id="explore" class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-brand-text text-center mb-3">استكشف أقسام الموقع</h2>
    <h4 class="text-sm text-gold text-center">الكتب والإصدارات، الأرشيف الصوتي والمرئي، الصور والوثائق، والبرامج — في
      مكان واحد</h4>
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach ($widgets as $widget)
        <div class="bg-brand-third shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
          {{-- <img src="{{ asset('images/widgets/' . $widget['image']) }}" alt="{{ $widget['title'] }}"
            class="w-full h-48 object-cover rounded-md"> --}}
          <div class="w-16 h-16 rounded-md bg-ember/10 flex items-center justify-center">
            <i class="{{ $widget['icon'] }} text-2xl text-ember"></i>
          </div>
          <h3 class="text-xl font-semibold text-gray-800 mt-4">{{ $widget['title'] }}</h3>
          <p class="mt-4 text-gray-600">{{ $widget['description'] }}</p>
          <p class="mt-2 text-sm text-gray-500">عدد العناصر: {{ $widget['value'] }}</p>
          <a href="{{ $widget['url'] }}" class="mt-4 inline-block text-brand font-medium hover:underline">
            عرض المزيد
            <i class="fas fa-arrow-left mr-2"></i>
          </a>
        </div>
      @endforeach
    </div>
  </div>

  {{-- أحدث الأخبار والفعاليات --}}
  <div class="mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 bg-brand-third">
    <h2 class="text-3xl font-extrabold text-brand-text mb-3">أحدث الأخبار والفعاليات</h2>
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach ($events as $event)
        <div>
          <div
            class="relative bg-linear-120 from-[#e7ddc4] to-[#f3ede0] shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
            {{-- status --}}
            @if ($event['status'] === 'upcoming')
              <span
                class="inline-block absolute bg-gold text-brand-text text-xs font-semibold px-3 py-2 rounded-full mb-2">فعالية
                قادمة</span>
            @elseif ($event['status'] === 'ongoing')
              <span
                class="inline-block absolute bg-gold text-white text-xs font-semibold px-3 py-2 rounded-full mb-2">جاري</span>
            @elseif ($event['status'] === 'past')
              <span
                class="inline-block absolute bg-[#c9c1ac] text-brand-text text-xs font-semibold px-3 py-2 rounded-full mb-2">فعالية منتهية</span>
            @elseif($event['status'] === 'news')
              <span
                class="inline-block absolute bg-[#2f4a37] text-white text-xs font-semibold px-3 py-2 rounded-full mb-2">خبر</span>
                @elseif($event['status'] === 'new-release')
              <span
                class="inline-block absolute bg-ember text-white text-xs font-semibold px-3 py-2 rounded-full mb-2">إصدار جديد</span>
            @endif
            <img src="{{ asset('images/events/' . $event['image']) }}" alt="{{ $event['title'] }}"
              class="w-full h-48 object-cover rounded-md">
          </div>
          <h3 class="text-xl font-semibold text-gray-800 mt-4">{{ $event['title'] }}</h3>
          {{-- <p class="mt-4 text-gray-600">{{ $event['description'] }}</p> --}}
          <p class="mt-2 text-sm text-gray-500"><i class="fa-regular fa-calendar ml-2"></i> {{ $event['date'] }}</p>
          <a href="{{ $event['slug'] }}" class="mt-4 inline-block text-brand font-medium hover:underline">
            عرض المزيد
            <i class="fas fa-arrow-left mr-2"></i>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</div>
