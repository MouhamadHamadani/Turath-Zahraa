<nav>
  <div class="flex items-center justify-between py-6 px-10 h-24 bg-main-bg text-brand-text">
    <div class="text-lg font-semibold flex">
      <x-logo />
      <p class="max-w-40">مؤسسة إحياء تراث الصديقة الشهيدة (ع)</p>
    </div>
    <div class="flex gap-7">
      <x-nav-link route="home">الرئيسية</x-nav-link>
      <x-nav-link route="about-us">عن المؤسسة</x-nav-link>
      <x-nav-link route="explore">تراث السيدة الزهراء</x-nav-link>
      {{-- dropdwon --}}
      <x-nav-link route="explore">المكتبة</x-nav-link>
      <x-nav-link route="explore">الأرشيف</x-nav-link>
      <x-nav-link route="explore">الأخبار والفعاليات</x-nav-link>
      <x-nav-link route="explore">تواصل معنا</x-nav-link>
    </div>
    {{-- search icon --}}
    <div class="flex items-center gap-4">
      <a href="{{-- route('search') --}}" class="hover:text-brand bg-[#f3ede0] p-2 rounded-full duration-300 w-9 h-9 flex items-center justify-center">
        <i class="fas fa-search"></i>
      </a>
    </div>
</nav>
