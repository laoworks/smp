@extends('layouts.public')

@php
    $contactCards = [
        [
            'label' => 'Telepon',
            'value' => $profil?->telepon ?: 'Belum diatur',
            'href' => filled($profil?->telepon) ? 'tel:' . preg_replace('/\s+/', '', $profil->telepon) : null,
        ],
        [
            'label' => 'Email',
            'value' => $profil?->email ?: 'Belum diatur',
            'href' => filled($profil?->email) ? 'mailto:' . $profil->email : null,
        ],
        [
            'label' => 'Website',
            'value' => $profil?->website ?: 'Belum diatur',
            'href' => $websiteUrl,
        ],
        [
            'label' => 'GPS / Lokasi',
            'value' => $fullAddress !== '' ? 'Buka lokasi sekolah' : 'Menggunakan nama sekolah untuk pencarian lokasi',
            'href' => $mapUrl,
        ],
    ];
@endphp

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <div class="grid gap-6 sm:gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
            <div class="max-w-3xl">
                <p class="section-kicker">{{ $pageTitle }}</p>
                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                    Hubungi {{ $profil?->nama_sekolah ?: 'Sekolah' }}
                </h1>
                <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $pageDescription }}</p>

                <div class="mt-7 grid gap-4 sm:mt-8 sm:grid-cols-2">
                    @foreach($contactCards as $card)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card">
                            <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                            @if($card['href'])
                                <a
                                    href="{{ $card['href'] }}"
                                    @if(\Illuminate\Support\Str::startsWith($card['href'], ['http://', 'https://'])) target="_blank" rel="noreferrer noopener" @endif
                                    class="mt-2 inline-flex text-base font-semibold leading-7 text-[var(--brand-primary)]"
                                >
                                    {{ $card['value'] }}
                                </a>
                            @else
                                <p class="mt-2 text-base font-semibold leading-7 text-slate-900">{{ $card['value'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 soft-card sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Informasi Utama</p>
                <div class="mt-4 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Nama Sekolah</p>
                        <p class="mt-2 text-lg font-bold text-slate-950">{{ $profil?->nama_sekolah ?: 'Belum diatur' }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">NPSN</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $profil?->npsn ?: '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">NSS</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $profil?->nss ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Alamat Lengkap</p>
                        <p class="mt-2 text-sm font-semibold leading-7 text-slate-900">{{ $fullAddress !== '' ? $fullAddress : 'Alamat sekolah belum diatur.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8 sm:py-10" data-reveal>
        <div class="grid gap-6 lg:grid-cols-[0.78fr_1.22fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal>
                <p class="section-kicker">Form Kontak</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">Kirim pesan ke sekolah</h2>
                <p class="mt-4 text-sm leading-7 text-slate-600">
                    Gunakan formulir ini untuk bertanya, meminta informasi, atau menyampaikan kebutuhan terkait sekolah.
                </p>

                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Respon</p>
                        <p class="mt-2 text-sm font-semibold leading-7 text-slate-900">
                            Pesan yang dikirim akan masuk ke panel admin agar dapat ditindaklanjuti oleh sekolah.
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Kontak Cepat</p>
                        <div class="mt-2 space-y-2 text-sm font-semibold text-slate-900">
                            <p>{{ $profil?->telepon ?: 'Telepon belum diatur' }}</p>
                            <p>{{ $profil?->email ?: 'Email belum diatur' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal data-reveal-delay="90">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <p class="font-semibold">Periksa kembali form kontak.</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.contact.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="nama" class="mb-2 block text-sm font-semibold text-slate-800">Nama Lengkap</label>
                            <input
                                id="nama"
                                name="nama"
                                type="text"
                                value="{{ old('nama') }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-800">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                                placeholder="nama@email.com"
                                required
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="no_hp" class="mb-2 block text-sm font-semibold text-slate-800">Nomor HP</label>
                            <input
                                id="no_hp"
                                name="no_hp"
                                type="text"
                                value="{{ old('no_hp') }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                                placeholder="08xxxxxxxxxx"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="subjek" class="mb-2 block text-sm font-semibold text-slate-800">Subjek</label>
                            <input
                                id="subjek"
                                name="subjek"
                                type="text"
                                value="{{ old('subjek') }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                                placeholder="Tulis subjek pesan"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="pesan" class="mb-2 block text-sm font-semibold text-slate-800">Pesan</label>
                            <textarea
                                id="pesan"
                                name="pesan"
                                rows="7"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-7 text-slate-900 outline-none transition focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                                placeholder="Tulis pesan Anda di sini"
                                required
                            >{{ old('pesan') }}</textarea>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm leading-7 text-slate-500">
                            Pastikan email dan nomor HP aktif agar sekolah dapat menghubungi kembali.
                        </p>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-[var(--brand-primary)] px-6 py-3 text-sm font-semibold text-white transition hover:opacity-95"
                        >
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="grid gap-6 py-8 sm:gap-8 sm:py-10 lg:grid-cols-[0.95fr_1.05fr]" data-reveal>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="section-kicker">Data Kontak</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">Informasi lengkap sekolah</h2>
                </div>
                <a
                    href="{{ $mapUrl }}"
                    target="_blank"
                    rel="noreferrer noopener"
                    class="inline-flex shrink-0 items-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-950"
                >
                    Buka Google Maps
                </a>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Alamat</p>
                    <p class="mt-2 text-sm font-semibold leading-7 text-slate-900">{{ $profil?->alamat ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Desa / Kelurahan</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $profil?->desa ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Kecamatan</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $profil?->kecamatan ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Kabupaten / Kota</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $profil?->kabupaten ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Provinsi</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $profil?->provinsi ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Kode Pos</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $profil?->kode_pos ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Telepon</p>
                    @if(filled($profil?->telepon))
                        <a href="tel:{{ preg_replace('/\s+/', '', $profil->telepon) }}" class="mt-2 inline-flex text-sm font-semibold text-[var(--brand-primary)]">
                            {{ $profil->telepon }}
                        </a>
                    @else
                        <p class="mt-2 text-sm font-semibold text-slate-900">-</p>
                    @endif
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">Email</p>
                    @if(filled($profil?->email))
                        <a href="mailto:{{ $profil->email }}" class="mt-2 inline-flex text-sm font-semibold text-[var(--brand-primary)]">
                            {{ $profil->email }}
                        </a>
                    @else
                        <p class="mt-2 text-sm font-semibold text-slate-900">-</p>
                    @endif
                </div>
                <div class="rounded-2xl border border-slate-200 p-4 sm:col-span-2">
                    <p class="text-sm text-slate-500">Website</p>
                    @if($websiteUrl)
                        <a href="{{ $websiteUrl }}" target="_blank" rel="noreferrer noopener" class="mt-2 inline-flex text-sm font-semibold text-[var(--brand-primary)]">
                            {{ $profil->website }}
                        </a>
                    @else
                        <p class="mt-2 text-sm font-semibold text-slate-900">-</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-5 soft-card sm:p-6" data-reveal data-reveal-delay="100">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="section-kicker">GPS Sekolah</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">Lokasi pada peta</h2>
                </div>
            </div>

            <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                <iframe
                    src="{{ $mapEmbedUrl }}"
                    class="h-[320px] w-full sm:h-[420px]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                    title="Peta lokasi sekolah"
                ></iframe>
            </div>

            <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Titik GPS / Pencarian Lokasi</p>
                <p class="mt-2 text-sm font-semibold leading-7 text-slate-900">
                    {{ $fullAddress !== '' ? $fullAddress : ($profil?->nama_sekolah ?: 'Lokasi sekolah belum diatur.') }}
                </p>
                <p class="mt-2 text-sm leading-7 text-slate-600">
                    Peta menampilkan lokasi sekolah berdasarkan alamat yang tersimpan pada profil sekolah.
                </p>
            </div>
        </div>
    </section>
@endsection
