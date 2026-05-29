@extends('layouts.public')

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <div class="max-w-4xl">
            <p class="section-kicker">{{ $pageTitle }}</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">{{ $pageTitle }}</h1>
            <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $pageDescription }}</p>
        </div>
    </section>

    <section class="py-8 sm:py-10" data-reveal>
        @if($cards->count() > 0)
            <div class="grid gap-8 sm:gap-10 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="space-y-6 sm:space-y-8">
                    @foreach($cards as $card)
                        <article class="border-b border-slate-200 pb-6 sm:pb-8 last:border-b-0 last:pb-0" data-reveal data-reveal-delay="{{ 70 * ($loop->index + 1) }}">
                            <div class="grid gap-5 sm:gap-6 sm:grid-cols-[220px_1fr] sm:items-start">
                                <div class="overflow-hidden rounded-2xl bg-slate-100">
                                    @if($card['image'])
                                        <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="h-52 w-full object-cover sm:h-40">
                                    @else
                                        <div class="flex h-52 w-full items-center justify-center text-sm font-semibold text-slate-500 sm:h-40">
                                            Belum ada gambar
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    @if(!empty($card['meta']))
                                        <div class="flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                            @foreach($card['meta'] as $meta)
                                                <span>{{ $meta }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <h2 class="mt-3 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">{{ $card['title'] }}</h2>
                                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $card['excerpt'] }}</p>

                                    <a
                                        href="{{ $card['href'] }}"
                                        class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3"
                                    >
                                        Lihat detail
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
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6" data-reveal data-reveal-delay="120">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Ringkasan</p>
                        <h2 class="mt-3 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">{{ $pageTitle }}</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $pageDescription }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6" data-reveal data-reveal-delay="180">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Jumlah Data</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">{{ number_format($cards->total()) }}</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Konten pada halaman ini ditampilkan dalam format yang lebih mudah dibaca dan dijelajahi.</p>
                    </div>
                </aside>
            </div>

            <div class="mt-8">
                {{ $cards->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500">
                {{ $emptyText }}
            </div>
        @endif
    </section>
@endsection
