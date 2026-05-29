@extends('layouts.public')

@php
    $coverUrl = $album->cover ? asset('storage/' . $album->cover) : optional($album->foto->first())->foto_url;
@endphp

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <a href="{{ route('public.gallery.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke galeri
        </a>

        <div class="mt-5 grid items-center gap-6 sm:gap-8 lg:grid-cols-[1fr_0.95fr]">
            <div>
                <p class="section-kicker">Album Galeri</p>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">{{ $album->nama_album }}</h1>
                <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">
                    {{ $album->deskripsi ?: 'Dokumentasi album kegiatan sekolah.' }}
                </p>

                <div class="mt-6 flex flex-wrap gap-4 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    @if($album->tanggal)
                        <span>{{ $album->tanggal->format('d M Y') }}</span>
                    @endif
                    <span>{{ $album->foto->count() }} foto</span>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                @if($coverUrl)
                    <img src="{{ $coverUrl }}" alt="{{ $album->nama_album }}" class="h-full min-h-[240px] w-full object-cover sm:min-h-[320px]">
                @else
                    <div class="flex min-h-[240px] items-center justify-center bg-slate-100 text-slate-500 sm:min-h-[320px]">
                        Cover album belum tersedia
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="py-8 sm:py-10" data-reveal>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal>
        <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Foto dalam Album</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($album->foto as $photo)
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                    <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                        @if($photo->foto_url)
                            <img src="{{ $photo->foto_url }}" alt="{{ $photo->judul ?: 'Foto album' }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm font-semibold text-slate-400">Foto belum tersedia</div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-slate-900">{{ $photo->judul ?: 'Dokumentasi Kegiatan' }}</h3>
                        <p class="mt-2 text-sm leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit(trim(strip_tags($photo->deskripsi ?: 'Dokumentasi kegiatan dalam album sekolah.')), 110) }}
                        </p>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500 sm:col-span-2 xl:col-span-3">
                    Album ini belum memiliki foto.
                </div>
            @endforelse
        </div>
        </div>
    </section>

    <section class="border-t border-slate-200 py-10" data-reveal>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal>
        <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Album Lainnya</h2>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @forelse($relatedAlbums as $related)
                <a href="{{ route('public.gallery.show', $related) }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-5 text-sm font-semibold text-slate-700 transition hover:border-violet-200 hover:text-[var(--brand-primary)]">
                    {{ $related->nama_album }}
                </a>
            @empty
                <p class="text-sm leading-7 text-slate-500 md:col-span-3">Belum ada album lainnya.</p>
            @endforelse
        </div>
        </div>
    </section>
@endsection
