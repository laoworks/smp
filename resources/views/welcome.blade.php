@extends('layouts.public')

@php
    $schoolName = $profil?->nama_sekolah ?: ($settings?->nama_website ?: config('app.name', 'Website Sekolah'));
    $heroImagePath = $profil?->gambar_ilustrasi ?: $profil?->gambar_kepala_sekolah ?: $profil?->logo;
    $heroImageUrl = filled($heroImagePath) ? asset('storage/' . $heroImagePath) : null;
    $heroSummary = \Illuminate\Support\Str::limit(
        trim(strip_tags($profil?->sejarah ?: $profil?->sambutan_kepala_sekolah ?: 'Sekolah berkomitmen membangun karakter, kompetensi, dan masa depan peserta didik melalui pembelajaran yang aktif, aman, dan relevan dengan perkembangan zaman.')),
        220
    );
    $locationLine = collect([
        $profil?->desa,
        $profil?->kecamatan,
        $profil?->kabupaten,
    ])->filter()->implode(', ');
    $featuredPrestasi = $prestasi->first();
    $featuredNews = $berita->first();
    $headmasterImagePath = $profil?->gambar_kepala_sekolah ?: $profil?->logo;
    $headmasterImageUrl = filled($headmasterImagePath) ? asset('storage/' . $headmasterImagePath) : null;
    $headmasterGreeting = trim((string) ($profil?->sambutan_kepala_sekolah ?: ''));
    if ($headmasterGreeting !== '') {
        if (str_contains($headmasterGreeting, '<')) {
            $headmasterGreetingHtml = $headmasterGreeting;
        } else {
            $normalizedGreeting = preg_replace("/\r\n?/", "\n", $headmasterGreeting);
            $lines = collect(explode("\n", $normalizedGreeting))
                ->map(fn ($line) => trim($line))
                ->filter(fn ($line) => $line !== '')
                ->values();
            $formattedBlocks = [];
            $listItems = [];
            $collectListItems = false;
            $flushList = function () use (&$formattedBlocks, &$listItems) {
                if ($listItems === []) {
                    return;
                }

                $formattedBlocks[] = '<ul>' . collect($listItems)
                    ->map(fn ($item) => '<li>' . e($item) . '</li>')
                    ->implode('') . '</ul>';
                $listItems = [];
            };

            foreach ($lines as $line) {
                $cleanLine = trim((string) preg_replace('/^([-*•]|\d+[.)])\s+/', '', $line));
                $startsWithClosing = preg_match('/^(terima|terimah|wassalamu|wassalam)/i', $cleanLine) === 1;
                $isExplicitListItem = preg_match('/^([-*•]|\d+[.)])\s+/', $line) === 1;
                $isListHeading = str_ends_with($cleanLine, ':');

                if ($collectListItems && ! $startsWithClosing && ! $isListHeading) {
                    $listItems[] = $cleanLine;
                    continue;
                }

                if ($isExplicitListItem) {
                    $collectListItems = true;
                    $listItems[] = $cleanLine;
                    continue;
                }

                $flushList();

                if ($cleanLine !== '') {
                    $formattedBlocks[] = '<p>' . e($cleanLine) . '</p>';
                }

                $collectListItems = $isListHeading;
            }

            $flushList();
            $headmasterGreetingHtml = $formattedBlocks !== []
                ? implode('', $formattedBlocks)
                : '<p>' . nl2br(e($headmasterGreeting)) . '</p>';
        }
    } else {
        $headmasterGreetingHtml = '<p>Sambutan kepala sekolah belum ditambahkan.</p>';
    }
    $heroNotes = collect([
        $profil?->akreditasi ? 'Akreditasi ' . $profil->akreditasi : null,
        $profil?->tahun_berdiri ? 'Berdiri sejak ' . $profil->tahun_berdiri : null,
        $locationLine !== '' ? $locationLine : null,
    ])->filter()->values();
    $headmasterGreetingExcerpt = \Illuminate\Support\Str::limit(trim(strip_tags($headmasterGreeting ?: 'Sambutan kepala sekolah belum ditambahkan.')), 240);
    $historyExcerpt = \Illuminate\Support\Str::limit(
        trim(strip_tags($profil?->sejarah ?: 'Profil sekolah menampilkan identitas, sejarah, visi, misi, dan informasi penting lainnya dalam halaman khusus.')),
        320
    );
    $leadNews = $berita->first();
    $newsHighlights = $berita->skip(1)->take(3);
    $featuredFacility = $fasilitas->first();
    $featuredFacilityImageUrl = filled($featuredFacility?->gambar) ? asset('storage/' . $featuredFacility->gambar) : null;
    $otherFacilities = $fasilitas->skip(1)->take(3);
    $activityLead = $ekstrakurikuler->first();
    $otherActivities = $ekstrakurikuler->skip(1)->take(3);
    $galleryPreviewImages = collect();
    if (filled($galleryHighlight?->cover)) {
        $galleryPreviewImages->push([
            'src' => asset('storage/' . $galleryHighlight->cover),
            'alt' => $galleryHighlight->nama_album ?: 'Galeri Sekolah',
        ]);
    }
    foreach (($galleryPhotos ?? collect()) as $photo) {
        if (filled($photo->foto)) {
            $galleryPreviewImages->push([
                'src' => asset('storage/' . $photo->foto),
                'alt' => $photo->judul ?: ($galleryHighlight?->nama_album ?: 'Galeri Sekolah'),
            ]);
        }
    }
    $galleryPreviewImages = $galleryPreviewImages
        ->unique('src')
        ->take(4)
        ->values();
    $prestasiPreviewItems = ($prestasiHighlights ?? collect())
        ->map(function ($item) {
            $imagePath = $item->foto ?: $item->sertifikat;

            return [
                'src' => filled($imagePath) ? asset('storage/' . $imagePath) : null,
                'alt' => $item->judul ?: 'Prestasi Sekolah',
                'title' => $item->judul ?: 'Prestasi Sekolah',
                'meta' => collect([$item->tingkat, $item->tahun])->filter()->implode(' • '),
            ];
        })
        ->filter(fn ($item) => filled($item['src']))
        ->take(4)
        ->values();
    $quickAccessLinks = collect([
        ['label' => 'Profil Sekolah', 'route' => route('public.profile')],
        ['label' => 'PPDB', 'route' => route('public.ppdb.index')],
        ['label' => 'Galeri', 'route' => route('public.gallery.index')],
        ['label' => 'Kontak', 'route' => route('public.contact')],
    ]);
    $quickMenus = collect([
        [
            'title' => 'Profil Sekolah',
            'description' => 'Halaman khusus untuk identitas, sejarah, visi, misi, dan informasi utama sekolah.',
            'route' => route('public.profile'),
        ],
        [
            'title' => 'Struktur Organisasi',
            'description' => 'Lihat susunan organisasi sekolah dan informasi visualnya dalam halaman tersendiri.',
            'route' => route('public.structure'),
        ],
        [
            'title' => 'Fasilitas',
            'description' => 'Daftar sarana belajar dan ruang pendukung kegiatan siswa secara lebih lengkap.',
            'route' => route('public.facilities.index'),
        ],
        [
            'title' => 'Galeri',
            'description' => 'Dokumentasi foto kegiatan sekolah dalam halaman galeri yang terpisah.',
            'route' => route('public.gallery.index'),
        ],
        [
            'title' => 'Alumni',
            'description' => 'Jejak alumni sekolah yang melanjutkan studi dan karier di berbagai bidang.',
            'route' => route('public.alumni.index'),
        ],
        [
            'title' => 'Kalender Akademik',
            'description' => 'Agenda sekolah, kegiatan akademik, dan jadwal penting yang sudah dipublikasikan.',
            'route' => route('public.calendar.index'),
        ],
        [
            'title' => 'PPDB',
            'description' => 'Informasi gelombang pendaftaran peserta didik baru yang sedang berjalan.',
            'route' => route('public.ppdb.index'),
        ],
    ]);
@endphp

@section('content')
    <section class="border-b border-slate-200 pb-8 sm:pb-10" data-reveal>
        <div class="grid gap-8 sm:gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
            <div>
                <p class="section-kicker">Website Sekolah</p>
                <h1 class="mt-4 max-w-4xl text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    {{ $schoolName }}
                </h1>
                <p class="mt-4 max-w-3xl text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">
                    {{ $heroSummary }}
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    <a
                        href="{{ route('public.profile') }}"
                        class="inline-flex items-center justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Lihat Profil Sekolah
                    </a>
                    <a
                        href="{{ route('public.news.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-950 hover:text-slate-950"
                    >
                        Baca Berita
                    </a>
                </div>

                <div class="mt-8 flex flex-wrap gap-3 sm:mt-10">
                    @foreach($heroNotes as $note)
                        <span class="border-b border-slate-300 pb-1 text-sm font-semibold text-slate-600">
                            {{ $note }}
                        </span>
                    @endforeach
                </div>

                <div class="mt-10 grid gap-4 border-t border-slate-200 pt-6 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach($stats as $stat)
                        <div data-reveal data-reveal-delay="{{ 70 * ($loop->index + 1) }}">
                            <p class="text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">{{ number_format($stat['value']) }}+</p>
                            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-5" data-reveal data-reveal-delay="120">
                <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white soft-card">
                    <div class="aspect-[16/10] min-h-[220px] overflow-hidden bg-slate-100 sm:min-h-0">
                        @if($heroImageUrl)
                            <img src="{{ $heroImageUrl }}" alt="{{ $schoolName }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full min-h-[220px] items-center justify-center text-sm font-semibold text-slate-500 sm:min-h-[300px]">
                                Gambar sekolah belum tersedia
                            </div>
                        @endif
                    </div>
                    <div class="grid gap-6 p-5 sm:p-6 lg:grid-cols-[1fr_auto] lg:items-end">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--brand-primary)]">Sorotan Sekolah</p>
                            <h2 class="mt-3 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">
                                {{ $leadNews?->judul ?: ($featuredPrestasi?->judul ?: 'Informasi terbaru sekolah akan tampil di sini') }}
                            </h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                {{ \Illuminate\Support\Str::limit(trim(strip_tags($leadNews?->konten ?: $featuredPrestasi?->deskripsi ?: 'Halaman depan ini menampilkan ringkasan informasi yang paling sering dicari oleh siswa, orang tua, dan masyarakat.')), 150) }}
                            </p>
                        </div>
                        <div class="lg:max-w-[14rem]">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Akses Cepat</p>
                            <div class="mt-3 grid gap-2 border-l border-slate-200 pl-4">
                                @foreach($quickAccessLinks as $item)
                                    <a href="{{ $item['route'] }}" class="text-sm font-semibold text-slate-700 transition hover:text-[var(--brand-primary)]">
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <a
                            href="{{ $leadNews ? route('public.news.show', $leadNews) : route('public.achievements.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3 lg:col-span-2"
                        >
                            Lihat selengkapnya
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/>
                            </svg>
                        </a>
                    </div>
                </article>

            </div>
        </div>
    </section>

    <section class="grid gap-6 border-t border-slate-200 py-8 sm:gap-8 sm:py-10 lg:grid-cols-[0.82fr_1.18fr]" data-reveal>
        <div data-reveal>
            <p class="section-kicker">Sambutan Kepala Sekolah</p>
            <div class="mt-4 overflow-hidden rounded-[1.5rem] bg-slate-100">
                @if($headmasterImageUrl)
                    <img src="{{ $headmasterImageUrl }}" alt="{{ $profil?->nama_kepala_sekolah ?: 'Kepala Sekolah' }}" class="h-full min-h-[320px] w-full object-cover">
                @else
                    <div class="flex min-h-[320px] items-center justify-center px-6 text-center text-sm font-semibold text-slate-500">
                        Foto kepala sekolah belum tersedia
                    </div>
                @endif
            </div>
            <div class="mt-5 border-l-2 border-[var(--brand-primary)] pl-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Kepala Sekolah</p>
                <h3 class="mt-2 text-xl font-extrabold tracking-tight text-slate-950">
                    {{ $profil?->nama_kepala_sekolah ?: 'Nama kepala sekolah belum diatur' }}
                </h3>
            </div>
        </div>

        <div data-reveal data-reveal-delay="120">
            <p class="section-kicker">Sambutan</p>
            <h2 class="section-title mt-2">Sambutan Kepala Sekolah</h2>
            <div class="prose prose-slate mt-5 max-w-none border-t border-slate-200 pt-6">
                {!! $headmasterGreetingHtml !!}
            </div>
            <a href="{{ route('public.profile') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3">
                Buka profil sekolah
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/>
                </svg>
            </a>
        </div>
    </section>

    <section class="border-t border-slate-200 py-8 sm:py-10" data-reveal>
        <div class="mb-6">
            <p class="section-kicker">Galeri dan Prestasi</p>
            <h2 class="section-title mt-2">Dokumentasi kegiatan dan capaian yang paling dekat dengan keseharian sekolah</h2>
        </div>

        <div class="grid gap-10 lg:grid-cols-2">
            <div data-reveal>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Galeri</p>
                        <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950">
                            {{ $galleryHighlight?->nama_album ?: 'Dokumentasi Kegiatan' }}
                        </h3>
                    </div>
                    <a href="{{ route('public.gallery.index') }}" class="hidden text-sm font-semibold text-[var(--brand-primary)] sm:inline-flex">
                        Buka galeri
                    </a>
                </div>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                    {{ \Illuminate\Support\Str::limit(trim(strip_tags($galleryHighlight?->deskripsi ?: 'Album foto dan video sekolah tersusun dalam halaman terpisah untuk memudahkan pengunjung melihat dokumentasi kegiatan.')), 180) }}
                </p>
                <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-4">
                    @forelse($galleryPreviewImages as $image)
                        <div class="{{ $loop->first && $galleryPreviewImages->count() > 2 ? 'col-span-2' : '' }} overflow-hidden rounded-[1.35rem] bg-slate-100">
                            <img
                                src="{{ $image['src'] }}"
                                alt="{{ $image['alt'] }}"
                                class="{{ $loop->first && $galleryPreviewImages->count() > 2 ? 'h-64 sm:h-72' : 'h-40 sm:h-44' }} w-full object-cover"
                            >
                        </div>
                    @empty
                        <div class="col-span-2 flex h-64 items-center justify-center rounded-[1.35rem] bg-slate-100 px-6 text-center text-sm font-semibold text-slate-500 sm:h-72">
                            Dokumentasi galeri belum tersedia
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 flex items-center justify-between gap-4 border-t border-slate-200 pt-4">
                    <p class="text-sm font-semibold text-slate-500">
                        {{ $galleryHighlight ? (($galleryHighlight->foto_count ?? $galleryPreviewImages->count()) . ' foto dalam dokumentasi ini') : ($galleryPreviewImages->count() . ' foto dokumentasi terbaru') }}
                    </p>
                    <a href="{{ route('public.gallery.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] sm:hidden">
                        Buka galeri
                    </a>
                </div>
            </div>

            <div data-reveal data-reveal-delay="100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Prestasi</p>
                        <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950">
                            {{ $featuredPrestasi?->judul ?: 'Capaian Siswa' }}
                        </h3>
                    </div>
                    <a href="{{ route('public.achievements.index') }}" class="hidden text-sm font-semibold text-[var(--brand-primary)] sm:inline-flex">
                        Lihat prestasi
                    </a>
                </div>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                    {{ \Illuminate\Support\Str::limit(trim(strip_tags($featuredPrestasi?->deskripsi ?: 'Arsip prestasi sekolah tersaji lebih rapi dan mudah ditelusuri agar setiap capaian siswa maupun sekolah dapat terlihat dengan baik.')), 180) }}
                </p>
                <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-4">
                    @forelse($prestasiPreviewItems as $item)
                        <div class="overflow-hidden rounded-[1.35rem] bg-slate-100">
                            <img src="{{ $item['src'] }}" alt="{{ $item['alt'] }}" class="h-44 w-full object-cover sm:h-48">
                        </div>
                    @empty
                        <div class="col-span-2 flex h-64 items-center justify-center rounded-[1.35rem] bg-slate-100 px-6 text-center text-sm font-semibold text-slate-500 sm:h-72">
                            Dokumentasi prestasi belum tersedia
                        </div>
                    @endforelse
                </div>
                @if($prestasiPreviewItems->isNotEmpty())
                    <div class="mt-5 grid gap-3 border-t border-slate-200 pt-4">
                        @foreach($prestasiPreviewItems->take(3) as $item)
                            <div class="flex items-start justify-between gap-4">
                                <p class="text-sm font-bold leading-6 text-slate-900">{{ $item['title'] }}</p>
                                <span class="shrink-0 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                    {{ $item['meta'] ?: 'Prestasi Sekolah' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="mt-4 flex items-center justify-between gap-4 border-t border-slate-200 pt-4">
                    <p class="text-sm font-semibold text-slate-500">
                        {{ collect([$featuredPrestasi?->tingkat, $featuredPrestasi?->tahun])->filter()->implode(' • ') ?: 'Prestasi terbaru sekolah' }}
                    </p>
                    <a href="{{ route('public.achievements.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] sm:hidden">
                        Lihat prestasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8 sm:py-10" data-reveal>
        <div class="mb-6 flex flex-col items-start justify-between gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-end sm:gap-4">
            <div>
                <p class="section-kicker">Rilis Terbaru</p>
                <h2 class="section-title mt-2">Informasi yang baru dipublikasikan untuk warga sekolah</h2>
            </div>
            <a href="{{ route('public.news.index') }}" class="hidden text-sm font-semibold text-[var(--brand-primary)] sm:inline-flex">Lihat semua berita</a>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.08fr_0.92fr]">
            <div>
                @if($leadNews)
                    @php
                        $leadNewsImage = filled($leadNews->gambar_utama) ? asset('storage/' . $leadNews->gambar_utama) : null;
                    @endphp
                    <article class="grid gap-5 lg:grid-cols-[1.05fr_0.95fr]" data-reveal>
                        <div class="overflow-hidden rounded-[1.5rem] bg-slate-100">
                            @if($leadNewsImage)
                                <img src="{{ $leadNewsImage }}" alt="{{ $leadNews->judul }}" class="h-full min-h-[320px] w-full object-cover">
                            @else
                                <div class="flex min-h-[320px] items-center justify-center px-4 text-center text-sm font-semibold text-slate-500">
                                    Gambar berita belum tersedia
                                </div>
                            @endif
                        </div>
                        <div class="border-t border-slate-200 pt-4 lg:border-t-0 lg:border-l lg:pl-6 lg:pt-0">
                            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                <span>{{ $leadNews->jenis ?: 'Berita Sekolah' }}</span>
                                @if($leadNews->tanggal_posting)
                                    <span>{{ $leadNews->tanggal_posting }}</span>
                                @endif
                            </div>
                            <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">{{ $leadNews->judul }}</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">
                                {{ \Illuminate\Support\Str::limit(trim(strip_tags($leadNews->konten ?: 'Informasi sekolah terbaru.')), 220) }}
                            </p>
                            <a href="{{ route('public.news.show', $leadNews) }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3">
                                Baca artikel
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-sm text-slate-500">
                        Belum ada berita yang dipublikasikan.
                    </div>
                @endif
            </div>

            <div class="border-l-0 border-slate-200 lg:border-l lg:pl-8" data-reveal data-reveal-delay="160">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Halaman Penting</p>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Beberapa halaman ini paling sering dibuka oleh siswa, orang tua, dan pengunjung yang ingin mengenal sekolah lebih dekat.
                </p>
                <div class="mt-5 grid gap-3">
                    @foreach($quickMenus as $item)
                        <a href="{{ $item['route'] }}" class="rounded-2xl border border-slate-200 px-4 py-4 transition hover:border-slate-300 hover:bg-slate-50">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-bold tracking-tight text-slate-950">{{ $item['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $item['description'] }}</p>
                                </div>
                                <span class="mt-1 text-[var(--brand-primary)]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($newsHighlights->isNotEmpty())
                    <div class="mt-8 border-t border-slate-200 pt-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Berita Lainnya</p>
                        <div class="mt-4 grid gap-4">
                            @foreach($newsHighlights as $item)
                                <a href="{{ route('public.news.show', $item) }}" class="border-b border-slate-100 pb-4 last:border-b-0 last:pb-0">
                                    <p class="text-sm font-bold leading-7 text-slate-900">{{ $item->judul }}</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        {{ \Illuminate\Support\Str::limit(trim(strip_tags($item->konten ?: 'Informasi sekolah terbaru.')), 90) }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="grid gap-6 border-t border-slate-200 py-8 sm:gap-8 sm:py-10 lg:grid-cols-[0.95fr_1.05fr]" data-reveal>
        <div data-reveal>
            <p class="section-kicker">Tentang Sekolah</p>
            <h2 class="section-title mt-2">Profil singkat yang memperkenalkan arah dan karakter sekolah</h2>
            <p class="mt-5 text-base leading-8 text-slate-600">
                {{ $historyExcerpt }}
            </p>
            <a href="{{ route('public.profile') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3">
                Buka halaman profil
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/>
                </svg>
            </a>
        </div>

        <div data-reveal data-reveal-delay="120">
            <div class="flex flex-col items-start justify-between gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Sorotan Prestasi</p>
                    <h2 class="mt-2 text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">
                        {{ $featuredPrestasi?->judul ?: 'Prestasi sekolah akan tampil di sini' }}
                    </h2>
                </div>
                <a href="{{ route('public.achievements.index') }}" class="text-sm font-semibold text-[var(--brand-primary)]">
                    Semua prestasi
                </a>
            </div>

            <p class="mt-5 text-sm leading-7 text-slate-600">
                {{ \Illuminate\Support\Str::limit(trim(strip_tags($featuredPrestasi?->deskripsi ?: 'Prestasi sekolah kini memiliki halaman tersendiri agar lebih mudah dibaca dan dikelola sebagai arsip pencapaian.')), 180) }}
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Jenis</p>
                    <p class="mt-2 text-sm font-semibold text-slate-700">{{ $featuredPrestasi?->jenis ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Tingkat</p>
                    <p class="mt-2 text-sm font-semibold text-slate-700">{{ $featuredPrestasi?->tingkat ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Tahun</p>
                    <p class="mt-2 text-sm font-semibold text-slate-700">{{ $featuredPrestasi?->tahun ?: '-' }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 border-t border-slate-200 py-8 sm:gap-8 sm:py-10 lg:grid-cols-2" data-reveal>
        <div data-reveal>
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <p class="section-kicker">Kegiatan Siswa</p>
                    <h2 class="section-title mt-2">Ekstrakurikuler dan aktivitas sekolah</h2>
                </div>
            </div>

            <div class="mt-6 grid gap-4">
                @forelse($ekstrakurikuler as $item)
                    <article class="rounded-[1.5rem] border border-slate-200 bg-white p-5 soft-card" data-reveal data-reveal-delay="{{ 70 * ($loop->index + 1) }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Ekstrakurikuler</p>
                                <h3 class="mt-2 text-lg font-bold tracking-tight text-slate-950">{{ $item->nama_ekskul }}</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit(trim(strip_tags($item->deskripsi ?: 'Kegiatan pengembangan minat dan bakat siswa.')), 120) }}
                                </p>
                            </div>
                            @if($item->jadwal_latihan)
                                <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $item->jadwal_latihan }}</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-sm text-slate-500">
                        Data ekstrakurikuler belum tersedia.
                    </div>
                @endforelse
                <a href="{{ route('public.extracurriculars.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)]">
                    Buka halaman ekstrakurikuler
                </a>
            </div>
        </div>

        <div data-reveal data-reveal-delay="100">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <p class="section-kicker">Fasilitas</p>
                    <h2 class="section-title mt-2">Sarana pendukung pembelajaran</h2>
                </div>
                <a href="{{ route('public.facilities.index') }}" class="text-sm font-semibold text-[var(--brand-primary)]">Lihat semua</a>
            </div>

            <div class="mt-6 grid gap-4">
                @forelse($fasilitas as $item)
                    <article class="rounded-[1.5rem] border border-slate-200 bg-white p-5 soft-card" data-reveal data-reveal-delay="{{ 80 * ($loop->index + 1) }}">
                        <div class="grid gap-4 sm:grid-cols-[160px_1fr] sm:items-start">
                            <div class="overflow-hidden rounded-2xl bg-slate-100">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_fasilitas }}" class="h-36 w-full object-cover">
                                @else
                                    <div class="flex h-36 items-center justify-center px-4 text-center text-sm font-semibold text-slate-500">
                                        Gambar belum tersedia
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-bold tracking-tight text-slate-950">{{ $item->nama_fasilitas }}</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit(trim(strip_tags($item->deskripsi ?: 'Informasi fasilitas sekolah.')), 120) }}
                                </p>
                                @if($item->jumlah)
                                    <span class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $item->jumlah }} unit</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-sm text-slate-500">
                        Data fasilitas belum tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
