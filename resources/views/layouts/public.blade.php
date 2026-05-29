@php
    $websiteSettings = \Illuminate\Support\Facades\Cache::remember(
        'website_settings',
        now()->addMinutes(30),
        fn() => \App\Models\PengaturanWebsite::query()->first()
    );
    $profilSekolah = \Illuminate\Support\Facades\Cache::remember(
        'profil_sekolah_front',
        now()->addMinutes(30),
        fn() => \App\Models\ProfilSekolah::query()->latest('id')->first()
    );

    $schoolName = $profilSekolah?->nama_sekolah ?: ($websiteSettings?->nama_website ?: config('app.name', 'Website Sekolah'));
    $logoUrl = filled($websiteSettings?->logo)
        ? asset('storage/' . $websiteSettings->logo)
        : (filled($profilSekolah?->logo) ? asset('storage/' . $profilSekolah->logo) : null);
    $searchQuery = trim((string) request('q', ''));

    $publicMenus = [
        ['label' => 'Beranda', 'route' => route('home'), 'active' => 'home', 'group' => 'main'],
        ['label' => 'PPDB', 'route' => route('public.ppdb.index'), 'active' => 'public.ppdb.*', 'group' => 'main'],
        ['label' => 'Kontak', 'route' => route('public.contact'), 'active' => 'public.contact', 'group' => 'main'],
        ['label' => 'Profil Sekolah', 'route' => route('public.profile'), 'active' => 'public.profile', 'group' => 'profil'],
        ['label' => 'Struktur Organisasi', 'route' => route('public.structure'), 'active' => 'public.structure', 'group' => 'profil'],
        ['label' => 'Guru', 'route' => route('public.teachers.index'), 'active' => 'public.teachers.*', 'group' => 'profil'],
        ['label' => 'Alumni', 'route' => route('public.alumni.index'), 'active' => 'public.alumni.*', 'group' => 'profil'],
        ['label' => 'Fasilitas', 'route' => route('public.facilities.index'), 'active' => 'public.facilities.*', 'group' => 'akademik'],
        ['label' => 'Kalender Akademik', 'route' => route('public.calendar.index'), 'active' => 'public.calendar.*', 'group' => 'akademik'],
        ['label' => 'Ekstrakurikuler', 'route' => route('public.extracurriculars.index'), 'active' => 'public.extracurriculars.*', 'group' => 'akademik'],
        ['label' => 'Prestasi', 'route' => route('public.achievements.index'), 'active' => 'public.achievements.*', 'group' => 'akademik'],
        ['label' => 'Berita', 'route' => route('public.news.index'), 'active' => 'public.news.*', 'group' => 'informasi'],
        ['label' => 'Galeri', 'route' => route('public.gallery.index'), 'active' => 'public.gallery.*', 'group' => 'informasi'],
        ['label' => 'CBT', 'route' => 'https://cbt.smpnegeri01namrole.sch.id/login', 'active' => null, 'group' => 'layanan', 'external' => true],
        ['label' => 'Asesmen', 'route' => 'https://asesmen.erlanggaonline.co.id/', 'active' => null, 'group' => 'layanan', 'external' => true],
        ['label' => 'Sarana Guru', 'route' => 'https://saranaguru.erlanggaonline.co.id/user/login', 'active' => null, 'group' => 'layanan', 'external' => true],
        ['label' => 'E-Library', 'route' => 'https://e-library.erlanggaonline.co.id/user/TWpVMk56RT0', 'active' => null, 'group' => 'layanan', 'external' => true],
    ];

    $desktopPrimaryMenus = collect($publicMenus)
        ->filter(fn ($item) => $item['group'] === 'main' && $item['label'] !== 'Kontak')
        ->values();
    $desktopTrailingMenus = collect($publicMenus)
        ->filter(fn ($item) => $item['label'] === 'Kontak')
        ->values();
    $mobilePrimaryMenus = collect($publicMenus)
        ->filter(fn ($item) => $item['group'] === 'main')
        ->values();
    $responsiveMenus = collect($publicMenus)
        ->values();
    $isMenuActive = fn(array $item): bool => filled($item['active'] ?? null) && request()->routeIs($item['active']);
    $menuDropdownGroups = [
        [
            'key' => 'profil',
            'label' => 'Profil',
            'items' => collect($publicMenus)->where('group', 'profil')->values()->all(),
        ],
        [
            'key' => 'akademik',
            'label' => 'Akademik',
            'items' => collect($publicMenus)->where('group', 'akademik')->values()->all(),
        ],
        [
            'key' => 'informasi',
            'label' => 'Informasi',
            'items' => collect($publicMenus)->where('group', 'informasi')->values()->all(),
        ],
        [
            'key' => 'layanan',
            'label' => 'Layanan',
            'items' => collect($publicMenus)->where('group', 'layanan')->values()->all(),
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ isset($pageTitle) ? $pageTitle . ' - ' . $schoolName : $schoolName }}</title>
        @include('components.favicon')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --brand-primary: oklch(45.7% 0.24 277.023);
                --brand-primary-soft: rgba(124, 58, 237, 0.08);
                --brand-primary-deep: oklch(31% 0.18 277.023);
                --brand-border: rgba(148, 163, 184, 0.18);
            }

            html {
                scroll-behavior: smooth;
            }

            [x-cloak] {
                display: none !important;
            }

            .public-shell {
                background: #f8fafc;
            }

            .soft-card {
                box-shadow: 0 12px 32px rgba(15, 23, 42, 0.05);
            }

            .section-kicker {
                color: var(--brand-primary);
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.18em;
                text-transform: uppercase;
            }

            .section-title {
                color: #0f172a;
                font-size: clamp(1.75rem, 3vw, 2.5rem);
                font-weight: 800;
                letter-spacing: -0.03em;
                line-height: 1.08;
            }

            .page-divider {
                border-top: 1px solid #e2e8f0;
            }

            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            [data-reveal] {
                opacity: 0;
                transform: translateY(24px);
                transition: opacity 700ms ease, transform 700ms cubic-bezier(0.22, 1, 0.36, 1);
                will-change: opacity, transform;
            }

            [data-reveal].is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            @media (max-width: 640px) {
                .section-kicker {
                    font-size: 0.68rem;
                    letter-spacing: 0.14em;
                }

                .section-title {
                    font-size: 1.6rem;
                    line-height: 1.15;
                }

                .soft-card {
                    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
                }

                .prose {
                    font-size: 0.95rem;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                html {
                    scroll-behavior: auto;
                }

                [data-reveal] {
                    opacity: 1;
                    transform: none;
                    transition: none;
                }
            }
        </style>
        @stack('styles')
    </head>
    <body
        x-data="{ mobileMenuOpen: false, openDropdown: null, scrolled: window.scrollY > 16 }"
        @scroll.window="scrolled = window.scrollY > 16"
        class="font-sans antialiased public-shell text-slate-900"
    >
        <div class="flex flex-col min-h-screen">
            <header
                class="sticky top-0 z-50 transition-all duration-300"
                :class="scrolled ? 'border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur' : 'border-b border-transparent bg-transparent'"
            >
                <div class="px-4 mx-auto max-w-[112rem] sm:px-6 lg:px-8">
                    <div class="relative flex h-[4.75rem] items-center justify-between sm:h-[5.5rem] md:grid md:grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] md:items-center md:gap-8 lg:gap-10 xl:gap-12">
                        <div class="absolute inset-y-0 right-0 flex items-center md:hidden">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center transition rounded-lg h-11 w-11 text-slate-500 hover:bg-slate-100 hover:text-slate-900"
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                :aria-expanded="mobileMenuOpen.toString()"
                                aria-label="Buka menu"
                            >
                                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                </svg>
                                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center justify-start min-w-0">
                            <a href="{{ route('home') }}" class="flex items-center gap-4 shrink-0">
                                <div class="flex items-center justify-center w-11 h-11 overflow-hidden bg-white border rounded-lg border-slate-200 sm:h-12 sm:w-12">
                                    @if($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $schoolName }}" class="object-cover h-10 w-10 sm:h-11 sm:w-11">
                                    @else
                                        <span class="text-sm font-bold text-slate-900">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($schoolName, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="min-w-0 max-w-[calc(100vw-6rem)] sm:max-w-none">
                                    <p class="text-[15px] font-bold tracking-wide truncate text-slate-900 sm:text-lg">{{ $schoolName }}</p>
                                    <p class="truncate text-xs text-slate-500 sm:text-sm">Website Informasi Sekolah</p>
                                </div>
                            </a>
                        </div>

                        <div class="hidden min-w-0 justify-self-center xl:flex">
                            <nav class="min-w-0">
                                <div class="flex items-center justify-center gap-1.5 pb-1">
                                    @foreach($desktopPrimaryMenus as $item)
                                        @php($itemIsActive = $isMenuActive($item))
                                        <a
                                            href="{{ $item['route'] }}"
                                            @if(!empty($item['external'])) target="_blank" rel="noreferrer noopener" @endif
                                            class="rounded-md px-[1.125rem] py-3 text-[17px] font-semibold tracking-[0.01em] transition {{ $itemIsActive ? 'text-[var(--brand-primary)]' : 'text-slate-600 hover:text-slate-950' }}"
                                        >
                                            {{ $item['label'] }}
                                        </a>
                                    @endforeach

                                    <?php foreach ($menuDropdownGroups as $menuGroup): ?>
                                        <?php
                                            $groupIsActive = collect($menuGroup['items'])->contains(
                                                fn ($item) => filled($item['active'] ?? null) && request()->routeIs($item['active'])
                                            );
                                            $groupKey = $menuGroup['key'];
                                        ?>
                                        <div
                                            class="relative"
                                            data-group-key="<?= e($groupKey) ?>"
                                            x-on:mouseenter="openDropdown = $el.dataset.groupKey"
                                            x-on:mouseleave="openDropdown = null"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-1.5 rounded-md px-[1.125rem] py-3 text-[17px] font-semibold tracking-[0.01em] transition <?= $groupIsActive ? 'text-[var(--brand-primary)]' : 'text-slate-600 hover:text-slate-950' ?>"
                                                x-on:click="openDropdown = openDropdown === $el.parentElement.dataset.groupKey ? null : $el.parentElement.dataset.groupKey"
                                            >
                                                <?= e($menuGroup['label']) ?>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                                                </svg>
                                            </button>

                                            <div
                                                x-show="openDropdown === $el.parentElement.dataset.groupKey"
                                                x-cloak
                                                x-transition.opacity.duration.150ms
                                                class="absolute left-0 w-64 py-2 mt-2 overflow-hidden bg-white border shadow-2xl top-full rounded-xl border-slate-200"
                                            >
                                                <?php foreach ($menuGroup['items'] as $item): ?>
                                                    <?php $itemIsActive = $isMenuActive($item); ?>
                                                    <a
                                                        href="<?= e($item['route']) ?>"
                                                        <?php if (!empty($item['external'])): ?>target="_blank" rel="noreferrer noopener"<?php endif; ?>
                                                        class="block px-4 py-3 text-sm font-medium transition <?= $itemIsActive ? 'bg-slate-100 text-slate-950' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950' ?>"
                                                    >
                                                        <span class="inline-flex items-center gap-2">
                                                            <?= e($item['label']) ?>
                                                            <?php if (!empty($item['external'])): ?>
                                                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5m0 0v5m0-5L10 14"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14v4a1 1 0 01-1 1h-12a1 1 0 01-1-1V6a1 1 0 011-1h4"/>
                                                                </svg>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    @foreach($desktopTrailingMenus as $item)
                                        @php($itemIsActive = $isMenuActive($item))
                                        <a
                                            href="{{ $item['route'] }}"
                                            @if(!empty($item['external'])) target="_blank" rel="noreferrer noopener" @endif
                                            class="rounded-md px-[1.125rem] py-3 text-[17px] font-semibold tracking-[0.01em] transition {{ $itemIsActive ? 'text-[var(--brand-primary)]' : 'text-slate-600 hover:text-slate-950' }}"
                                        >
                                            {{ $item['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </nav>
                        </div>

                        <div class="hidden items-center justify-end gap-4 justify-self-end pl-6 lg:gap-5 lg:pl-8 md:flex">
                            <form action="{{ route('public.search') }}" method="GET" class="relative w-full max-w-[16rem] lg:max-w-[18rem]">
                                <label for="navbar-search" class="sr-only">Cari</label>
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </span>
                                <input
                                    id="navbar-search"
                                    name="q"
                                    type="search"
                                    value="{{ $searchQuery }}"
                                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-9 pr-4 text-[15px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                                    placeholder="Cari informasi..."
                                >
                            </form>
                            @auth
                                <a
                                    href="{{ route('dashboard') }}"
                                    class="rounded-lg bg-[var(--brand-primary)] px-5 py-3 text-[16px] font-semibold text-white shadow-sm transition hover:opacity-95"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="rounded-lg bg-[var(--brand-primary)] px-5 py-3 text-[16px] font-semibold text-white shadow-sm transition hover:opacity-95"
                                >
                                    Login
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="hidden border-t border-slate-200/80 md:block xl:hidden">
                        <div class="flex items-center gap-3 py-4 overflow-x-auto no-scrollbar">
                            @foreach($responsiveMenus as $item)
                                @php($itemIsActive = $isMenuActive($item))
                                <a
                                    href="{{ $item['route'] }}"
                                    @if(!empty($item['external'])) target="_blank" rel="noreferrer noopener" @endif
                                    class="whitespace-nowrap rounded-full border px-[1.125rem] py-3 text-[16px] font-semibold transition {{ $itemIsActive ? 'border-[color:var(--brand-primary)] bg-[var(--brand-primary-soft)] text-[var(--brand-primary)]' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:text-slate-950' }}"
                                >
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div
                    x-show="mobileMenuOpen"
                    x-cloak
                    x-transition.opacity.duration.200ms
                    class="border-t border-slate-200 bg-white/95 backdrop-blur md:hidden"
                >
                    <div class="mx-auto max-h-[calc(100vh-4.5rem)] overflow-y-auto px-4 pb-4 pt-2 sm:max-h-[calc(100vh-5rem)] sm:px-6 lg:px-8">
                        <div class="grid gap-3 max-w-[112rem] sm:grid-cols-2">
                        <div class="p-2 space-y-1 border rounded-xl border-slate-200 sm:col-span-2">
                            <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Pencarian</p>
                            <form action="{{ route('public.search') }}" method="GET" class="relative">
                                <label for="mobile-search" class="sr-only">Cari</label>
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </span>
                                <input
                                    id="mobile-search"
                                    name="q"
                                    type="search"
                                    value="{{ $searchQuery }}"
                                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-9 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                                    placeholder="Cari informasi..."
                                >
                            </form>
                        </div>
                        <div class="p-2 space-y-1 border rounded-xl border-slate-200">
                            <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Menu Utama</p>
                            @foreach($mobilePrimaryMenus as $item)
                                @php($itemIsActive = $isMenuActive($item))
                                <a
                                    href="{{ $item['route'] }}"
                                    @if(!empty($item['external'])) target="_blank" rel="noreferrer noopener" @endif
                                    class="block rounded-md px-3.5 py-2.5 text-[17px] font-semibold transition {{ $itemIsActive ? 'bg-[var(--brand-primary-soft)] text-[var(--brand-primary)]' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-950' }}"
                                    @click="mobileMenuOpen = false"
                                >
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>

                        @foreach($menuDropdownGroups as $menuGroup)
                            <div class="p-2 space-y-1 border rounded-xl border-slate-200">
                                <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $menuGroup['label'] }}</p>
                                @foreach($menuGroup['items'] as $item)
                                    @php($itemIsActive = $isMenuActive($item))
                                    <a
                                        href="{{ $item['route'] }}"
                                        @if(!empty($item['external'])) target="_blank" rel="noreferrer noopener" @endif
                                        class="flex items-center justify-between rounded-md px-3.5 py-2.5 text-[17px] font-semibold transition {{ $itemIsActive ? 'bg-[var(--brand-primary-soft)] text-[var(--brand-primary)]' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-950' }}"
                                        @click="mobileMenuOpen = false"
                                    >
                                        <span>{{ $item['label'] }}</span>
                                        @if(!empty($item['external']))
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5m0 0v5m0-5L10 14"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14v4a1 1 0 01-1 1h-12a1 1 0 01-1-1V6a1 1 0 011-1h4"/>
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="p-2 space-y-1 border rounded-xl border-slate-200 sm:col-span-2">
                            <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Akun</p>
                            @auth
                                <a
                                    href="{{ route('dashboard') }}"
                                    class="block rounded-md bg-[var(--brand-primary)] px-3 py-2.5 text-center text-base font-semibold text-white transition hover:opacity-95"
                                    @click="mobileMenuOpen = false"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="block rounded-md bg-[var(--brand-primary)] px-3 py-2.5 text-center text-base font-semibold text-white transition hover:opacity-95"
                                    @click="mobileMenuOpen = false"
                                >
                                    Login
                                </a>
                            @endauth
                        </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex flex-col flex-1 w-full px-4 pt-6 mx-auto max-w-7xl sm:px-6 sm:pt-8 lg:px-8">
                <main class="flex-1">
                    @yield('content')
                </main>

                <footer id="kontak" class="pt-8 mt-12 border-t border-slate-200 sm:mt-14">
                    <div class="flex flex-col gap-6 sm:gap-8 lg:flex-row lg:items-start lg:justify-between">
                        <a href="{{ route('home') }}" class="flex items-center min-w-0 gap-3">
                            <div class="flex items-center justify-center overflow-hidden rounded-lg h-11 w-11 bg-slate-900 outline outline-1 -outline-offset-1 outline-slate-800">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ $schoolName }}" class="object-cover h-9 w-9">
                                @else
                                    <span class="text-lg font-bold text-white">
                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($schoolName, 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-base font-extrabold tracking-tight truncate text-slate-950">{{ $schoolName }}</p>
                                <p class="text-sm truncate text-slate-500">Website Informasi Sekolah</p>
                            </div>
                        </a>

                        <div class="lg:max-w-2xl lg:flex-1">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Tentang</p>
                            <p class="max-w-2xl mt-3 text-sm leading-7 text-slate-600">
                                {{ $profilSekolah?->alamat ?: 'Alamat sekolah belum diatur.' }}
                            </p>
                            <div class="grid gap-2 mt-4 text-sm text-slate-600">
                                @if(filled($profilSekolah?->telepon))
                                    <p>Telepon: {{ $profilSekolah->telepon }}</p>
                                @endif
                                @if(filled($profilSekolah?->email))
                                    <p>Email: {{ $profilSekolah->email }}</p>
                                @endif
                                @if(filled($profilSekolah?->website))
                                    <a href="{{ $profilSekolah->website }}" target="_blank" rel="noreferrer noopener" class="font-medium text-[var(--brand-primary)]">
                                        {{ $profilSekolah->website }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap text-sm font-semibold gap-x-4 gap-y-3 sm:gap-x-5">
                            @foreach($publicMenus as $item)
                                <a href="{{ $item['route'] }}" class="text-slate-600 transition hover:text-[var(--brand-primary)]">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 py-5 mt-8 text-sm border-t border-slate-200 text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:gap-3">
                        <p>&copy; {{ now()->year }} {{ $schoolName }}. Semua hak dilindungi.</p>
                        <p>Dibangun untuk menyajikan informasi sekolah secara jelas dan terstruktur.</p>
                    </div>
                </footer>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const items = document.querySelectorAll('[data-reveal]');

                if (!items.length) {
                    return;
                }

                if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    items.forEach((item) => item.classList.add('is-visible'));
                    return;
                }

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        const delay = entry.target.dataset.revealDelay;
                        if (delay) {
                            entry.target.style.transitionDelay = `${delay}ms`;
                        }

                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -40px 0px',
                });

                items.forEach((item) => observer.observe(item));
            });
        </script>
    </body>
</html>
