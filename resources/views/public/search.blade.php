@extends('layouts.public')

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <div class="max-w-4xl">
            <p class="section-kicker">{{ $pageTitle }}</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">Hasil pencarian</h1>
            <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $pageDescription }}</p>

            <form action="{{ route('public.search') }}" method="GET" class="mt-6 max-w-2xl">
                <label for="search-page" class="sr-only">Cari</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        </svg>
                    </span>
                    <input
                        id="search-page"
                        name="q"
                        type="search"
                        value="{{ $keyword }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary-soft)]"
                        placeholder="Cari berita, guru, fasilitas, prestasi, dan lainnya..."
                    >
                </div>
            </form>

            @if($keyword !== '')
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="rounded-full bg-[var(--brand-primary-soft)] px-4 py-2 text-sm font-semibold text-[var(--brand-primary)]">
                        Kata kunci: {{ $keyword }}
                    </span>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                        {{ $resultCount }} hasil ditemukan
                    </span>
                </div>
            @endif
        </div>
    </section>

    <section class="py-8 sm:py-10" data-reveal>
        @if($keyword === '')
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500">
                Masukkan kata kunci pada form pencarian untuk mulai mencari informasi.
            </div>
        @elseif($results->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500">
                Tidak ada hasil yang cocok dengan pencarian Anda.
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="space-y-6 sm:space-y-8">
                    @foreach($results as $item)
                        <article class="border-b border-slate-200 pb-6 last:border-b-0 last:pb-0" data-reveal data-reveal-delay="{{ 50 * (($loop->index % 4) + 1) }}">
                            <div class="grid gap-4 sm:grid-cols-[220px_1fr] sm:items-start">
                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="h-52 w-full object-cover sm:h-40">
                                    @else
                                        <div class="flex h-52 w-full items-center justify-center px-4 text-center text-sm font-semibold text-slate-500 sm:h-40">
                                            Gambar belum tersedia
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        <span>{{ $item['section'] }}</span>
                                        @foreach($item['meta'] as $meta)
                                            <span>{{ $meta }}</span>
                                        @endforeach
                                    </div>
                                    <h2 class="mt-3 text-xl font-bold tracking-tight text-slate-950 sm:text-2xl">{{ $item['title'] }}</h2>
                                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $item['excerpt'] }}</p>
                                    <a href="{{ $item['href'] }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3">
                                        Buka hasil
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="space-y-4 sm:space-y-5">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6" data-reveal data-reveal-delay="100">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Ringkasan</p>
                        <h2 class="mt-3 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">Kategori hasil</h2>
                        <div class="mt-5 grid gap-3">
                            @foreach($sectionCounts as $section => $count)
                                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <span class="text-sm font-semibold text-slate-700">{{ $section }}</span>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-600">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6" data-reveal data-reveal-delay="160">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Saran Pencarian</p>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            Gunakan kata kunci seperti nama guru, judul berita, fasilitas, jenis prestasi, atau kegiatan sekolah agar hasil lebih relevan.
                        </p>
                    </div>
                </aside>
            </div>
        @endif
    </section>
@endsection
