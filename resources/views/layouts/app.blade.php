<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>{{ $title ?? config('app.name') }}</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles
</head>

<body class="font-cairo bg-main-bg">
  {{-- Load Navigation Component --}}
  @livewire('navigation')

  <main class="min-h-screen">
    {{ $slot }}
  </main>

  {{-- Footer --}}
  <footer class="bg-[#0b1410] text-second-text py-6">
    <div class="container max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
      <div>
        <div class="flex items-center gap-2 mb-4">
          <div class="w-12 h-12 mb-4 rounded-full p-1 bg-[#fdfbf5]">
            <img src="{{ asset('images/logo_2_bg.png') }}" alt="Logo" class="w-full h-full object-contain">
          </div>
          <h3 class="text-lg font-semibold mb-2 max-w-40 text-main-text">مؤسسة إحياء تراث الصديقة الشهيدة (ع)</h3>
        </div>
        <p class="text-sm text-second-text">مؤسسة بحثية تعليمية غير ربحية، مقرها النجف الأشرف، تعنى بجمع وتوثيق وإحياء
          التراث الفاطمي.</p>
        <div class="flex space-x-4 mt-5">
          <a href="#" class="hover:text-gray-300"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="hover:text-gray-300"><i class="fab fa-twitter"></i></a>
          <a href="#" class="hover:text-gray-300"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
      <div>
        <h3 class="text-lg font-semibold mb-3 text-gold">روابط سريعة</h3>
        <ul class="space-y-2 text-third-text">
          {{-- <li><a href="{{ route('home') }}" class="hover:underline">الرئيسية</a></li> --}}
          <li><a href="{{ route('about-us') }}" class="hover:underline">عن المؤسسة</a></li>
          <li><a href="{{ route('explore') }}" class="hover:underline">المكتبة</a></li>
          <li><a href="{{ route('explore') }}" class="hover:underline">الأرشيف الصوتي والمرئي</a></li>
          <li><a href="{{ route('explore') }}" class="hover:underline">الأخبار والفعاليات</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-lg font-semibold mb-3 text-gold">الأقسام</h3>
        <ul class="space-y-2 text-third-text">
          <li><a href="{{ route('about-us') }}" class="hover:underline">تراث السيدة الزهراء</a></li>
          <li><a href="{{ route('explore') }}" class="hover:underline">الخطباء والباحثون</a></li>
          <li><a href="{{ route('explore') }}" class="hover:underline">المشاريع والبرامج</a></li>
          <li><a href="{{ route('explore') }}" class="hover:underline">التبرعات والدعم</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-lg font-semibold mb-2 text-gold">تواصل معنا</h3>
        <div class="space-y-2 text-third-text">
          <p class="text-sm">
            <i class="fas fa-map-marker ml-2"></i>العراق، النجف الأشرف</p>
          <p class="text-sm">
            <i class="fas fa-phone ml-2"></i>
            <a href="tel:" class="hover:underline" dir="ltr">+96412345</a>
          </p>
          <p class="text-sm">
            <i class="fas fa-envelope ml-2"></i>
            <a href="mailto:" class="hover:underline">instituation@example.com</a>
          </p>
        </div>
      </div>
    </div>
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between border-t border-white/10 pt-4">
      <p class="text-sm">&copy; {{ date('Y') }} مؤسسة إحياء تراث الصديقة الشهيدة (ع). جميع الحقوق محفوظة.</p>
      <div class="mt-4 md:mt-0 space-x-4">
        <a href="#" class="hover:underline text-sm">سياسة الخصوصية</a>
        <a href="#" class="hover:underline text-sm">شروط الاستخدام</a>
      </div>
    </div>
  </footer>

  @livewireScripts
</body>

</html>
