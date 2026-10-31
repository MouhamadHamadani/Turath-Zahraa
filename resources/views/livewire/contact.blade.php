<div>
  <x-page-header title="تواصل معنا" description="يسعدنا استقبال استفساراتكم واقتراحاتكم وإسهاماتكم في خدمة التراث الفاطمي." />

  <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
    <div class="lg:col-span-2">
      @if ($sent)
        <div class="rounded-lg border border-[#2f4a37]/30 bg-[#2f4a37]/5 p-8 text-center" role="status">
          <i class="fas fa-circle-check mb-4 text-4xl text-[#2f4a37]" aria-hidden="true"></i>
          <h2 class="text-xl font-extrabold text-brand-text">تم استلام رسالتك</h2>
          <p class="mt-2 text-brand-second-text">شكرًا لتواصلك. سنرد عليك على بريدك الإلكتروني في أقرب وقت ممكن.</p>
          <button type="button" wire:click="$set('sent', false)" class="mt-6 text-sm font-bold text-ember hover:text-gold">إرسال رسالة أخرى</button>
        </div>
      @else
        <form wire:submit="submit" novalidate class="space-y-5 rounded-lg border border-brand-text/10 bg-brand-third p-6 sm:p-8">
          @error('form')
            <p class="rounded-md bg-ember/10 p-3 text-sm text-ember" role="alert">{{ $message }}</p>
          @enderror

          {{-- Honeypot --}}
          <div class="absolute -start-[9999px]" aria-hidden="true">
            <label>الموقع الإلكتروني<input type="text" wire:model="website" tabindex="-1" autocomplete="off"></label>
          </div>

          <div class="grid gap-5 sm:grid-cols-2">
            <div>
              <label for="name" class="mb-1 block text-sm font-bold text-brand-text">الاسم <span class="text-ember">*</span></label>
              <input id="name" type="text" wire:model="name" autocomplete="name" required
                class="w-full rounded-md border border-brand-text/15 bg-main-bg px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
              @error('name') <p class="mt-1 text-xs text-ember">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="email" class="mb-1 block text-sm font-bold text-brand-text">البريد الإلكتروني <span class="text-ember">*</span></label>
              <input id="email" type="email" wire:model="email" autocomplete="email" dir="ltr" required
                class="w-full rounded-md border border-brand-text/15 bg-main-bg px-4 py-2.5 text-end text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
              @error('email') <p class="mt-1 text-xs text-ember">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="phone" class="mb-1 block text-sm font-bold text-brand-text">الهاتف <span class="text-xs font-normal text-brand-second-text">(اختياري)</span></label>
              <input id="phone" type="tel" wire:model="phone" autocomplete="tel" dir="ltr"
                class="w-full rounded-md border border-brand-text/15 bg-main-bg px-4 py-2.5 text-end text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
              @error('phone') <p class="mt-1 text-xs text-ember">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="subject" class="mb-1 block text-sm font-bold text-brand-text">الموضوع <span class="text-ember">*</span></label>
              <input id="subject" type="text" wire:model="subject" required
                class="w-full rounded-md border border-brand-text/15 bg-main-bg px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
              @error('subject') <p class="mt-1 text-xs text-ember">{{ $message }}</p> @enderror
            </div>
          </div>

          <div>
            <label for="message" class="mb-1 block text-sm font-bold text-brand-text">الرسالة <span class="text-ember">*</span></label>
            <textarea id="message" rows="6" wire:model="message" required
              class="w-full rounded-md border border-brand-text/15 bg-main-bg px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30"></textarea>
            @error('message') <p class="mt-1 text-xs text-ember">{{ $message }}</p> @enderror
          </div>

          <button type="submit" wire:loading.attr="disabled"
            class="rounded-lg bg-linear-to-r from-ember to-gold px-6 py-3 text-base font-extrabold text-button-text duration-300 hover:bg-linear-to-tr disabled:opacity-60">
            <span wire:loading.remove wire:target="submit">إرسال الرسالة</span>
            <span wire:loading wire:target="submit">جارٍ الإرسال...</span>
          </button>
        </form>
      @endif
    </div>

    <aside class="space-y-5">
      <div class="rounded-lg border border-brand-text/10 bg-brand-third p-6">
        <h2 class="mb-4 text-lg font-extrabold text-brand-text">بيانات التواصل</h2>
        <ul class="space-y-3 text-sm text-brand-text">
          @if (filled($contact->address))
            <li><i class="fas fa-location-dot me-2 text-gold" aria-hidden="true"></i>{{ $contact->address }}</li>
          @endif
          @if (filled($contact->phone))
            <li><i class="fas fa-phone me-2 text-gold" aria-hidden="true"></i><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}" dir="ltr" class="hover:underline">{{ $contact->phone }}</a></li>
          @endif
          @if (filled($contact->whatsapp))
            <li><i class="fab fa-whatsapp me-2 text-gold" aria-hidden="true"></i><a href="https://wa.me/{{ preg_replace('/\D/', '', $contact->whatsapp) }}" target="_blank" rel="noopener" dir="ltr" class="hover:underline">{{ $contact->whatsapp }}</a></li>
          @endif
          @if (filled($contact->email))
            <li><i class="fas fa-envelope me-2 text-gold" aria-hidden="true"></i><a href="mailto:{{ $contact->email }}" class="hover:underline">{{ $contact->email }}</a></li>
          @endif
        </ul>
        @if ($links = $contact->socialLinks())
          <div class="mt-5 flex gap-4 border-t border-brand-text/10 pt-4">
            @foreach ($links as $link)
              <a href="{{ $link['url'] }}" target="_blank" rel="noopener" aria-label="{{ $link['label'] }}" class="text-brand-text duration-300 hover:text-gold"><i class="{{ $link['icon'] }}" aria-hidden="true"></i></a>
            @endforeach
          </div>
        @endif
      </div>

      @if (filled($contact->map_embed_url) && str_starts_with($contact->map_embed_url, 'https://'))
        <div class="overflow-hidden rounded-lg border border-brand-text/10">
          <iframe src="{{ $contact->map_embed_url }}" title="موقع المؤسسة على الخريطة" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            class="h-72 w-full border-0"></iframe>
        </div>
      @endif
    </aside>
  </div>
</div>
