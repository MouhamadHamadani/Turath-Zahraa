@props(['title', 'description' => null])

<header class="border-b border-brand-text/10 bg-brand-third">
  <div class="mx-auto max-w-7xl px-4 py-12 text-center sm:px-6 lg:px-8">
    <h1 class="text-2xl font-extrabold text-brand-text sm:text-3xl">{{ $title }}</h1>
    @if ($description)
      <p class="mx-auto mt-3 max-w-3xl text-sm text-brand-second-text sm:text-base">{{ $description }}</p>
    @endif
    {{ $slot }}
  </div>
</header>
