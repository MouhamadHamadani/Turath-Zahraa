<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Log in</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans min-h-screen bg-gray-100 px-6 py-12 text-gray-900">
        <main class="mx-auto max-w-md rounded-lg bg-white p-8 shadow">
            <h1 class="text-2xl font-semibold">Log in</h1>

            @if (session('status'))
                <p class="mt-4 text-sm text-green-600">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-1 block w-full rounded border-gray-300">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="mt-1 block w-full rounded border-gray-300">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input name="remember" type="checkbox" value="1" class="rounded border-gray-300">
                    Remember me
                </label>

                <button type="submit" class="w-full rounded bg-gray-900 px-4 py-2 text-white hover:bg-gray-700">
                    Log in
                </button>
            </form>

            @if (Route::has('register'))
                <p class="mt-6 text-sm text-gray-600">
                    Need an account?
                    <a href="{{ route('register') }}" class="font-medium underline">Register</a>
                </p>
            @endif
        </main>
    </body>
</html>
