<nav x-data="{ open: false }" class="relative">
  <div class="flex items-center justify-between py-6 px-10 h-24 bg-main-bg text-brand-text">
    <div class="text-lg font-semibold flex">
      <x-logo />
      <p class="max-w-40 lg:block hidden">مؤسسة إحياء تراث الصديقة الشهيدة (ع)</p>
    </div>
    <div class="lg:flex hidden gap-7">
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
      <a href="{{-- route('search') --}}"
        class="hover:text-brand bg-[#f3ede0] p-2 rounded-full duration-300 w-9 h-9 flex items-center justify-center">
        <i class="fas fa-search"></i>
      </a>
      <div class="lg:hidden">
        <button id="menu-toggle" class="text-brand-text bg-[#f3ede0] p-2 pb-1 rounded-md focus:outline-none"
          @click="open = !open">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>
    </div>
  </div>
  <div id="mobile-menu" class="lg:hidden relative bg-main-bg" :class="{ 'block': open, 'hidden': !open }">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-main-bg text-brand-text flex flex-col gap-2 py-4 absolute w-full z-50">
      <x-nav-link route="home">الرئيسية</x-nav-link>
      <x-nav-link route="about-us">عن المؤسسة</x-nav-link>
      <x-nav-link route="explore">تراث السيدة الزهراء</x-nav-link>
      {{-- dropdwon --}}
      <x-nav-link route="explore">المكتبة</x-nav-link>
      <x-nav-link route="explore">الأرشيف</x-nav-link>
      <x-nav-link route="explore">الأخبار والفعاليات</x-nav-link>
      <x-nav-link route="explore">تواصل معنا</x-nav-link>
    </div>
  </div>
</nav>
