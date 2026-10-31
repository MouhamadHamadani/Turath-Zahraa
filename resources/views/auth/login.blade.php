<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>تسجيل الدخول</title>
        <link rel="stylesheet" href="{{ asset('fonts/Cairo/cairo.css') }}">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans min-h-screen bg-main-bg px-6 py-12 text-brand-text">
        <main class="mx-auto max-w-md rounded-lg border border-brand-text/10 bg-brand-third p-8 shadow">
            <a href="{{ route('home') }}" class="mb-6 block"><img src="{{ asset('images/logo_2_bg.png') }}" alt="شعار المؤسسة" class="mx-auto h-20 w-auto"></a>
            <h1 class="text-2xl font-extrabold">تسجيل الدخول</h1>

            @if (session('status'))
                <p class="mt-4 text-sm text-green-700">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold">البريد الإلكتروني</label>
                    <input id="email" name="email" type="email" dir="ltr" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-1 block w-full rounded-md border border-brand-text/20 bg-main-bg px-3 py-2 text-end focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
                    @error('email')
                        <p class="mt-1 text-sm text-ember">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold">كلمة المرور</label>
                    <input id="password" name="password" type="password" dir="ltr" required autocomplete="current-password" class="mt-1 block w-full rounded-md border border-brand-text/20 bg-main-bg px-3 py-2 text-end focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
                    @error('password')
                        <p class="mt-1 text-sm text-ember">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input name="remember" type="checkbox" value="1" class="rounded border-brand-text/30">
                    تذكّرني
                </label>

                <button type="submit" class="w-full rounded-lg bg-linear-to-r from-ember to-gold px-4 py-2.5 font-extrabold text-button-text hover:bg-linear-to-tr duration-300">
                    دخول
                </button>
            </form>
        </main>
    </body>
</html>
