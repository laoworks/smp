@php
    $websiteSettings = \Illuminate\Support\Facades\Cache::remember(
        'website_settings',
        now()->addMinutes(30),
        fn() => \App\Models\PengaturanWebsite::query()->first()
    );

    $faviconPath = $websiteSettings?->favicon ?: $websiteSettings?->logo;
    $faviconUrl = filled($faviconPath) ? asset('storage/' . $faviconPath) : null;
@endphp

@if($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
@endif
