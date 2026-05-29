@extends('layouts.public')

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <div class="max-w-4xl">
            <p class="section-kicker">{{ $pageTitle }}</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">{{ $pageTitle }}</h1>
            <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $pageDescription }}</p>
        </div>
    </section>

    <section class="py-8 sm:py-10">
        @if($structures->isNotEmpty())
            <div class="grid gap-6 sm:gap-8">
                @foreach($structures as $item)
                    <article class="grid gap-5 rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:gap-6 sm:p-6 lg:grid-cols-[1.05fr_0.95fr]" data-reveal data-reveal-delay="{{ 80 * ($loop->index + 1) }}">
                        <div>
                            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                <span>{{ filled($item->tahun) ? $item->tahun : 'Tanpa Tahun' }}</span>
                                <span>{{ $item->is_active ? 'Aktif' : 'Arsip' }}</span>
                            </div>

                            <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">
                                {{ $item->judul ?: 'Struktur Organisasi Sekolah' }}
                            </h2>

                            <div class="prose prose-slate mt-4 max-w-none">
                                {!! filled($item->deskripsi) ? $item->deskripsi : '<p>Deskripsi struktur organisasi belum ditambahkan.</p>' !!}
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                            @if(filled($item->gambar))
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul ?: 'Struktur Organisasi' }}" class="h-full min-h-[240px] w-full object-cover sm:min-h-[320px]">
                            @else
                                <div class="flex min-h-[240px] items-center justify-center text-sm font-semibold text-slate-500 sm:min-h-[320px]">
                                    Gambar struktur organisasi belum tersedia
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500" data-reveal>
                Struktur organisasi belum tersedia.
            </div>
        @endif
    </section>
@endsection
