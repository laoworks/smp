@extends('layouts.public')

@php
    $heroImage = filled($profil?->gambar_ilustrasi)
        ? asset('storage/' . $profil->gambar_ilustrasi)
        : (filled($profil?->logo) ? asset('storage/' . $profil->logo) : null);
    $misiImages = collect([
        $profil?->gambar_misi_1,
        $profil?->gambar_misi_2,
        $profil?->gambar_misi_3,
    ])->filter()->map(fn($path) => asset('storage/' . $path));
    $libraryStructure = filled($profil?->struktur_perpustakaan) ? asset('storage/' . $profil->struktur_perpustakaan) : null;
    $schoolOverview = [
        'NPSN' => $profil?->npsn ?: '-',
        'NSS' => $profil?->nss ?: '-',
        'Tahun Berdiri' => $profil?->tahun_berdiri ?: '-',
        'Tahun Akreditasi' => $profil?->tahun_akreditasi ?: '-',
    ];
    $contactDetails = [
        'Telepon' => $profil?->telepon ?: '-',
        'Email' => $profil?->email ?: '-',
        'Website' => $profil?->website ?: '-',
    ];
    $locationDetails = [
        'Alamat' => $profil?->alamat ?: '-',
        'Desa / Kelurahan' => $profil?->desa ?: '-',
        'Kecamatan' => $profil?->kecamatan ?: '-',
        'Kabupaten / Kota' => $profil?->kabupaten ?: '-',
        'Provinsi' => $profil?->provinsi ?: '-',
        'Kode Pos' => $profil?->kode_pos ?: '-',
    ];
@endphp

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <div class="grid items-center gap-6 sm:gap-8 lg:grid-cols-[1fr_0.9fr]">
            <div>
                <p class="section-kicker">{{ $pageTitle }}</p>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                    {{ $profil?->nama_sekolah ?: 'Profil Sekolah' }}
                </h1>
                <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $pageDescription }}</p>

                <div class="mt-7 grid gap-4 sm:mt-8 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-sm text-slate-500">Akreditasi</p>
                        <p class="mt-2 text-lg font-bold text-slate-950">{{ $profil?->akreditasi ?: 'Belum diatur' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-sm text-slate-500">Kepala Sekolah</p>
                        <p class="mt-2 text-lg font-bold text-slate-950">{{ $profil?->nama_kepala_sekolah ?: 'Belum diatur' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-sm text-slate-500">Pendiri</p>
                        <p class="mt-2 text-lg font-bold text-slate-950">{{ $profil?->pendiri ?: 'Belum diatur' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-sm text-slate-500">Tahun Berdiri</p>
                        <p class="mt-2 text-lg font-bold text-slate-950">{{ $profil?->tahun_berdiri ?: 'Belum diatur' }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                @if($heroImage)
                    <img src="{{ $heroImage }}" alt="{{ $profil?->nama_sekolah ?: 'Profil Sekolah' }}" class="h-full min-h-[240px] w-full object-cover sm:min-h-[320px]">
                @else
                    <div class="flex min-h-[240px] items-center justify-center bg-slate-100 text-slate-500 sm:min-h-[320px]">
                        Gambar profil belum tersedia
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="grid gap-5 py-8 sm:gap-6 sm:py-10 lg:grid-cols-2" data-reveal>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Sejarah</h2>
            <div class="prose prose-slate mt-4 max-w-none">
                <p>{{ trim(strip_tags($profil?->sejarah ?: 'Sejarah sekolah belum ditambahkan.')) }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal data-reveal-delay="80">
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Sambutan Kepala Sekolah</h2>
            <div class="prose prose-slate mt-4 max-w-none">
                @if(filled($profil?->sambutan_kepala_sekolah))
                    {!! $profil->sambutan_kepala_sekolah !!}
                @else
                    <p>Sambutan kepala sekolah belum ditambahkan.</p>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal data-reveal-delay="120">
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Visi</h2>
            <div class="prose prose-slate mt-4 max-w-none">
                <p>{{ trim(strip_tags($profil?->visi ?: 'Visi sekolah belum ditambahkan.')) }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal data-reveal-delay="160">
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Misi</h2>
            <div class="prose prose-slate mt-4 max-w-none">
                <p>{{ trim(strip_tags($profil?->misi ?: 'Misi sekolah belum ditambahkan.')) }}</p>
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200 py-10" data-reveal>
        <div class="mb-6">
            <p class="section-kicker">Informasi Sekolah</p>
            <h2 class="section-title mt-2">Data dasar sekolah</h2>
        </div>

        <div class="grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 soft-card sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Profil Singkat</p>
                <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">
                    {{ $profil?->nama_sekolah ?: 'Sekolah belum diatur' }}
                </h3>
                <p class="mt-4 text-sm leading-7 text-slate-600">
                    {{ $profil?->alamat ?: 'Alamat sekolah belum diatur.' }}
                </p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach($schoolOverview as $label => $value)
                        <div class="rounded-2xl bg-slate-50 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">{{ $label }}</p>
                            <p class="mt-2 text-base font-semibold text-slate-800">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 soft-card sm:p-7">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Kontak Resmi</p>
                    <div class="mt-5 space-y-4">
                        @foreach($contactDetails as $label => $value)
                            <div class="border-b border-slate-100 pb-4 last:border-b-0 last:pb-0">
                                <p class="text-sm text-slate-500">{{ $label }}</p>
                                @if($label === 'Website' && filled($profil?->website))
                                    <a href="{{ $profil->website }}" target="_blank" rel="noreferrer" class="mt-1 inline-flex text-base font-semibold text-[var(--brand-primary)]">
                                        {{ $value }}
                                    </a>
                                @else
                                    <p class="mt-1 text-base font-semibold text-slate-800">{{ $value }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 soft-card sm:p-7">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Wilayah Sekolah</p>
                    <div class="mt-5 space-y-4">
                        @foreach($locationDetails as $label => $value)
                            <div class="flex flex-col gap-1 border-b border-slate-100 pb-4 last:border-b-0 last:pb-0 sm:flex-row sm:items-start sm:justify-between sm:gap-6">
                                <p class="text-sm text-slate-500 sm:min-w-[9rem]">{{ $label }}</p>
                                <p class="text-base font-semibold leading-7 text-slate-800 sm:text-right">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200 py-10" data-reveal>
        <div class="mb-6">
            <p class="section-kicker">Galeri Profil</p>
            <h2 class="section-title mt-2">Visual pendukung profil sekolah</h2>
        </div>

        <div class="grid gap-5 sm:gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6">
                <h3 class="text-xl font-bold tracking-tight text-slate-900">Struktur Perpustakaan</h3>
                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                    @if($libraryStructure)
                        <img src="{{ $libraryStructure }}" alt="Struktur Perpustakaan" class="h-full min-h-[240px] w-full object-cover sm:min-h-[320px]">
                    @else
                        <div class="flex min-h-[240px] items-center justify-center text-sm font-semibold text-slate-500 sm:min-h-[320px]">
                            Struktur perpustakaan belum tersedia
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6">
                <h3 class="text-xl font-bold tracking-tight text-slate-900">Visual Misi Sekolah</h3>
                @if($misiImages->isNotEmpty())
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        @foreach($misiImages as $image)
                            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                <img src="{{ $image }}" alt="Visual misi sekolah" class="h-48 w-full object-cover sm:h-44">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-4 flex min-h-[240px] items-center justify-center rounded-2xl border border-slate-200 bg-slate-100 text-sm font-semibold text-slate-500 sm:min-h-[320px]">
                        Visual misi sekolah belum tersedia
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
