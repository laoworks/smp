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
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $schoolName }}</title>
        @include('components.favicon')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
