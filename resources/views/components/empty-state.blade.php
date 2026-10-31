@props(['icon' => 'fa-solid fa-folder-open'])

<div class="py-16 text-center text-brand-second-text">
  <i class="{{ $icon }} mb-4 text-4xl text-gold/60" aria-hidden="true"></i>
  <p class="mx-auto max-w-md text-sm sm:text-base">{{ $slot }}</p>
</div>
