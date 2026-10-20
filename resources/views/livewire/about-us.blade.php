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
      <img class="w-full h-full object-cover" src="{{ asset('images/hero.png') }}" alt="About Us Hero">
      {{-- <div class="absolute inset-0 bg-gray-900 bg-opacity-50"></div> --}}
    </div>
  </div>

  {{-- Who we are --}}
  <div class="bg-brand-third">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
        <div class="lg:w-1/2">
          <h1 class="text-sm font-extrabold text-ember mb-2">من نحن</h1>
          <h2 class="text-2xl text-brand-text font-extrabold">مؤسسة بحثية تراثية، غير ربحية، مقرّها النجف الأشرف</h2>
          <p class="mt-4 text-base text-[#4b4436]">
            مؤسسة إحياء تراث الصديقة الشهيدة (ع) مؤسسة بحثية وتعليمية غير ربحية، تُعنى بجمع التراث الفاطمي والشيعي
            وتوثيقه
            وأرشفته، ودعم الدراسات والبحوث المتعلقة به، وإيصاله إلى الباحثين وطلاب الحوزات والجامعات والمهتمين في أنحاء
            العالم العربي. تأسست المؤسسة لتكون مرجعًا موثوقًا يجمع بين الأصالة العلمية وسهولة الوصول الرقمي إلى هذا
            التراث.
          </p>
          <div class="mt-5 flex gap-4">
            @foreach ($info as $item)
              <div class="bg-main-bg rounded-xl px-5 py-3">
                <h3 class="text-base font-extrabold text-ember">{{ $item['value'] }}</h3>
                <span class="text-sm text-[#8a8272]">{{ $item['title'] }}</span>
              </div>
            @endforeach
          </div>
        </div>
        <div class="lg:w-1/2">
          <img src="{{ asset('images/about-us/who-we-are.jpg') }}" alt="Who we are" class="rounded-lg shadow-lg">
        </div>
      </div>
    </div>
  </div>

  {{-- Our vision/mission --}}
  <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
      {{-- vision --}}
      <div class="bg-brand-third rounded-2xl p-8 shadow-lg mb-8 md:mb-0 border border-dashed border-brand-text/10">
        <div class="flex items-center gap-4 mb-4 rounded-xl p-4 bg-gold/10 w-fit">
          <i class="fas fa-eye text-gold text-2xl"></i>
        </div>
        <h2 class="text-xl font-extrabold text-brand-text mb-4">الرؤية</h2>
        <p class="text-sm text-brand-second-text">
          أن تكون المؤسسة مرجعًا رائدًا في جمع وتوثيق وإحياء التراث الفاطمي والشيعي، وأن تسهم في تعزيز الوعي الثقافي
          والتاريخي بين الباحثين والمهتمين في العالم العربي.
        </p>
      </div>
      {{-- mission --}}
      <div class="bg-brand-third rounded-2xl p-8 shadow-lg mb-8 md:mb-0 border border-brand-text/10">
        <div class="flex items-center gap-4 mb-4 rounded-xl p-4 bg-ember/10 w-fit">
          <i class="fas fa-message text-ember text-2xl"></i>
        </div>
        <h2 class="text-xl font-extrabold text-brand-text mb-4">الرسالة</h2>
        <p class="text-sm text-brand-second-text">
          الحفاظ على تراث الصديقة الزهراء عليها السلام وإحياؤه، من خلال جمعه وتوثيقه وأرشفته ونشره، ودعم الدراسات
          والأبحاث المرتبطة به، وإيصاله إلى الأجيال الجديدة من الباحثين والمهتمين.
        </p>
      </div>
    </div>
  </div>

  {{-- Our Goals --}}
  <div class="bg-brand-third">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
      <h2 class="text-center text-2xl font-extrabold text-brand-text mb-2">أهداف المؤسسة</h2>
      <h3 class="text-center text-sm text-brand-second-text">ست غايات تحكم عمل المؤسسة في خدمة التراث الفاطمي</h3>

      <div class="mt-8 grid md:grid-cols-3 grid-cols-1 gap-5">
        @foreach ($goals as $i => $goal)
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

  {{-- Instituation Timeline --}}
  <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-extrabold text-gray-900 mb-8 text-center">مسيرة المؤسسة</h2>
    <div class="relative">
      <div class="border-l-2 border-dashed border-brand-text/10 absolute h-full left-1/2 transform -translate-x-1/2"></div>
      <div class="space-y-12">
        @foreach ($timeline as $event)
          <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8 relative">
            <div class="lg:w-1/2 order-last lg:order-first">
              <img src="{{ asset('images/about-us/timeline/' . $event['image']) }}" alt="{{ $event['title'] }}"
                class="rounded-lg shadow-lg w-full h-64 object-cover">
            </div>
            <div class="lg:w-1/2 bg-brand-text/5 p-6 rounded-lg shadow-lg relative z-10">
              <h3 class="text-sm font-bold text-gray-900">{{ $event['title'] }}</h3>
              <p class="mt-2 text-gray-600 text-sm">{{ $event['description'] }}</p>
              <span
                class="absolute -top-4 left-full transform -translate-x-full bg-ember text-white px-3 py-1 rounded-full text-sm">{{ $event['year'] }}</span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
