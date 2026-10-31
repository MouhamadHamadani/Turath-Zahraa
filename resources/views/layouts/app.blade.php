@php
  $general = app(\App\Settings\GeneralSettings::class);
  $contact = app(\App\Settings\ContactSettings::class);

  $siteName = $general->site_name;
  $pageTitle = filled($title ?? null) ? "{$title} | {$siteName}" : $siteName;
  $pageDescription = $description ?? $general->tagline;
  $canonicalUrl = url()->current();
  $socialImage = asset('images/logo_2.jpg');

  // Footer legal links appear only for pages that exist.
  $legalPages = \Illuminate\Support\Facades\Cache::remember(
      'footer.legal-pages',
      600,
      fn() => \App\Models\Page::published()
          ->whereIn('slug', ['privacy', 'terms'])
          ->pluck('title', 'slug')
          ->all(),
  );
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>{{ $pageTitle }}</title>
  <meta name="description" content="{{ $pageDescription }}">
  <link rel="canonical" href="{{ $canonicalUrl }}">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ $siteName }}">
  <meta property="og:title" content="{{ $pageTitle }}">
  <meta property="og:description" content="{{ $pageDescription }}">
  <meta property="og:url" content="{{ $canonicalUrl }}">
  <meta property="og:image" content="{{ $socialImage }}">
  <meta property="og:locale" content="ar_AR">
  <meta name="twitter:card" content="summary">

  <link rel="icon" type="image/png" href="{{ asset('/icon/favicon-96x96.png') }}" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('/icon/favicon.svg') }}" />
  <link rel="shortcut icon" href="{{ asset('/icon/favicon.ico') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/icon/apple-touch-icon.png') }}" />
  <link rel="manifest" href="{{ asset('/icon/site.webmanifest') }}" />

  <link rel="preload" href="{{ asset('fonts/Cairo/Cairo-Regular.ttf') }}" as="font" type="font/ttf" crossorigin>
  <link rel="preload" href="{{ asset('fonts/Cairo/Cairo-Bold.ttf') }}" as="font" type="font/ttf" crossorigin>
  <link rel="stylesheet" href="{{ asset('fonts/Cairo/cairo.css') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles
</head>

<body class="font-sans bg-main-bg">
  {{-- Load Navigation Component --}}
  @livewire('navigation')

  <main class="min-h-screen">
    {{ $slot }}
  </main>

  {{-- Footer --}}
  <footer class="bg-[#0b1410] text-second-text py-6">
    <div class="container max-w-7xl mx-auto grid sm:grid-cols-2 grid-cols-2 lg:grid-cols-4 gap-8 mb-10 px-7">
      <div class="col-span-2 sm:col-span-1 sm:border-b-0 border-b border-white/10 pb-4 sm:pb-0">
        <div class="flex sm:flex-row flex-col items-center gap-2 mb-4">
          <div class="w-12 h-12 mb-4 rounded-full p-1 bg-[#fdfbf5]">
            <img src="{{ asset('images/logo_2_bg.png') }}" alt="شعار المؤسسة" class="w-full h-full object-contain">
          </div>
          <h3 class="text-sm font-semibold mb-2 sm:max-w-40 text-main-text">{{ $siteName }}</h3>
        </div>
        <p class="sm:text-sm text-xs text-second-text sm:text-start text-center">{{ $general->tagline }}</p>
        @if ($socialLinks = $contact->socialLinks())
          <div class="flex gap-4 mt-5 sm:justify-start justify-center">
            @foreach ($socialLinks as $link)
              <a href="{{ $link['url'] }}" target="_blank" rel="noopener" aria-label="{{ $link['label'] }}"
                class="hover:text-gray-300"><i class="{{ $link['icon'] }}" aria-hidden="true"></i></a>
            @endforeach
          </div>
        @endif
      </div>
      <div>
        <h3 class="text-lg font-semibold mb-3 text-gold">روابط سريعة</h3>
        <ul class="space-y-2 text-third-text">
          <li><a href="{{ route('home') }}" wire:navigate class="hover:underline">الرئيسية</a></li>
          <li><a href="{{ route('about-us') }}" wire:navigate class="hover:underline">عن المؤسسة</a></li>
          <li><a href="{{ route('library.books') }}" wire:navigate class="hover:underline">المكتبة</a></li>
          <li><a href="{{ route('archive.audio') }}" wire:navigate class="hover:underline">الأرشيف الصوتي والمرئي</a>
          </li>
          <li><a href="{{ route('news-events') }}" wire:navigate class="hover:underline">الأخبار والفعاليات</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-lg font-semibold mb-3 text-gold">الأقسام</h3>
        <ul class="space-y-2 text-third-text">
          <li><a href="{{ route('heritage') }}" wire:navigate class="hover:underline">تراث السيدة الزهراء</a></li>
          <li><a href="{{ route('library.research') }}" wire:navigate class="hover:underline">البحوث</a></li>
          <li><a href="{{ route('library.publications') }}" wire:navigate class="hover:underline">المطبوعات
              والإصدارات</a></li>
          <li><a href="{{ route('archive.photos') }}" wire:navigate class="hover:underline">الصور</a></li>
          <li><a href="{{ route('archive.documents') }}" wire:navigate class="hover:underline">الوثائق</a></li>
        </ul>
      </div>
      <div class="col-span-2 sm:col-span-1 sm:border-t-0 border-t border-white/10 pt-4 sm:pt-0">
        <h3 class="text-lg font-semibold mb-2 text-gold">تواصل معنا</h3>
        <div class="space-y-2 text-third-text">
          @if (filled($contact->address))
            <p class="text-sm"><i class="fas fa-map-marker me-2" aria-hidden="true"></i>{{ $contact->address }}</p>
          @endif
          @if (filled($contact->phone))
            <p class="text-sm">
              <i class="fas fa-phone me-2" aria-hidden="true"></i>
              <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}" class="hover:underline"
                dir="ltr">{{ $contact->phone }}</a>
            </p>
          @endif
          @if (filled($contact->email))
            <p class="text-sm">
              <i class="fas fa-envelope me-2" aria-hidden="true"></i>
              <a href="mailto:{{ $contact->email }}" class="hover:underline">{{ $contact->email }}</a>
            </p>
          @endif
          <p class="text-sm">
            <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>
            <a href="{{ route('contact') }}" wire:navigate class="hover:underline">أرسل لنا رسالة</a>
          </p>
        </div>
      </div>
    </div>
    <div
      class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between border-t border-white/10 pt-4 px-7">
      <p class="text-sm">&copy; {{ date('Y') }} {{ $siteName }}. جميع الحقوق محفوظة.</p>
      @if ($legalPages)
        <div class="mt-4 lg:mt-0 flex gap-4">
          @foreach ($legalPages as $slug => $pageTitleText)
            <a href="{{ route('page.show', $slug) }}" wire:navigate
              class="hover:underline text-sm">{{ $pageTitleText }}</a>
          @endforeach
        </div>
      @endif
    </div>
  </footer>

  @livewireScripts
</body>

</html>
