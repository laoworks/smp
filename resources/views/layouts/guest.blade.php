<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        @include('components.favicon')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-[linear-gradient(180deg,#f7f5ff_0%,#ffffff_45%,#f8fafc_100%)]">
        @php
            $websiteSettings = \Illuminate\Support\Facades\Cache::remember(
                'website_settings',
                now()->addMinutes(30),
                fn() => \App\Models\PengaturanWebsite::query()->first()
            );
            $profilSekolah = \Illuminate\Support\Facades\Cache::remember(
                'profil_sekolah_front',
                now()->addMinutes(30),
                fn() => \App\Models\ProfilSekolah::query()->first()
            );

            $schoolName = $profilSekolah?->nama_sekolah ?: ($websiteSettings?->nama_website ?: config('app.name', 'Website Sekolah'));
            $logoUrl = filled($websiteSettings?->logo)
                ? asset('storage/' . $websiteSettings->logo)
                : (filled($profilSekolah?->logo) ? asset('storage/' . $profilSekolah->logo) : null);
        @endphp

        <div class="min-h-screen px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto flex min-h-[calc(100vh-3rem)] max-w-6xl flex-col justify-center">
                <div class="grid items-center gap-8 lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="hidden lg:block">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl bg-[oklch(96.5%_0.03_277.023)] ring-1 ring-violet-200/80">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ $schoolName }}" class="h-12 w-12 object-cover">
                                @else
                                    <span class="text-lg font-extrabold text-[oklch(45.7%_0.24_277.023)]">
                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($schoolName, 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="text-lg font-extrabold tracking-tight">{{ $schoolName }}</p>
                                <p class="text-sm text-slate-500">Akses akun dan layanan sekolah</p>
                            </div>
                        </a>

                        <div class="mt-10 max-w-xl">
                            <div class="inline-flex items-center rounded-full bg-[oklch(96.5%_0.03_277.023)] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[oklch(45.7%_0.24_277.023)] ring-1 ring-violet-200/80">
                                Portal Sekolah
                            </div>
                            <h1 class="mt-6 text-5xl font-extrabold leading-tight tracking-tight text-slate-900">
                                Tampilan login yang konsisten dengan halaman utama.
                            </h1>
                            <p class="mt-6 text-base leading-8 text-slate-600">
                                Masuk ke sistem untuk mengelola akun, memperbarui data profil, dan mengakses fitur yang sesuai dengan peran pengguna.
                            </p>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-3xl border border-white/80 bg-white/85 px-5 py-5 shadow-[0_24px_60px_rgba(15,23,42,0.10)]">
                                    <p class="text-sm text-slate-500">Desain</p>
                                    <p class="mt-2 text-lg font-bold">Selaras dengan halaman publik</p>
                                </div>
                                <div class="rounded-3xl border border-[oklch(45.7%_0.24_277.023/.15)] bg-[oklch(45.7%_0.24_277.023/.96)] px-5 py-5 text-white shadow-[0_24px_60px_rgba(91,33,182,0.18)]">
                                    <p class="text-sm text-violet-100">Akses</p>
                                    <p class="mt-2 text-lg font-bold">Cepat, bersih, dan fokus</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="overflow-hidden rounded-[2rem] border border-white/80 bg-white/90 shadow-[0_24px_60px_rgba(15,23,42,0.12)] backdrop-blur">
                            <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 lg:hidden">
                                    <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-[oklch(96.5%_0.03_277.023)] ring-1 ring-violet-200/80">
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" alt="{{ $schoolName }}" class="h-9 w-9 object-cover">
                                        @else
                                            <span class="text-sm font-extrabold text-[oklch(45.7%_0.24_277.023)]">
                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($schoolName, 0, 2)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-base font-extrabold tracking-tight">{{ $schoolName }}</p>
                                        <p class="text-xs text-slate-500">Portal Sekolah</p>
                                    </div>
                                </a>
                                <p class="hidden text-sm text-slate-500 lg:block">Silakan lanjutkan ke akun Anda</p>
                            </div>

                            <div class="px-6 py-6 sm:px-8 sm:py-8">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
