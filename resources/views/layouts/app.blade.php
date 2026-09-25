<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        <footer class="bg-gray-800 text-white py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
                <p class="text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.</p>
                <div class="mt-4 md:mt-0 space-x-4">
                    <a href="#" class="hover:underline">سياسة الخصوصية</a>
                    <a href="#" class="hover:underline">شروط الاستخدام</a>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
