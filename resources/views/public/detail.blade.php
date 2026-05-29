@extends('layouts.public')

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ $backLabel }}
        </a>

        <div class="mt-5 grid gap-6 sm:gap-8 lg:grid-cols-[0.95fr_1.05fr]">
            <div>
                <p class="section-kicker">{{ $sectionLabel }}</p>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">{{ $title }}</h1>
                <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $description }}</p>

                @if(!empty($meta))
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        @foreach($meta as $item)
                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                @if($image)
                    <img src="{{ $image }}" alt="{{ $title }}" class="h-full min-h-[240px] w-full object-cover sm:min-h-[320px]">
                @else
                    <div class="flex min-h-[240px] items-center justify-center bg-slate-100 text-slate-500 sm:min-h-[320px]">
                        Gambar belum tersedia
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="grid gap-6 py-8 sm:gap-8 sm:py-10 lg:grid-cols-[1.2fr_0.8fr]" data-reveal>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal>
            @foreach($contentSections as $section)
                <div class="{{ $loop->first ? '' : 'mt-8 pt-8 border-t border-slate-100' }}">
                    <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">{{ $section['title'] }}</h2>
                    <div class="prose prose-slate mt-4 max-w-none">
                        {!! $section['html'] !!}
                    </div>
                </div>
            @endforeach
        </div>

        <aside class="space-y-4 sm:space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal data-reveal-delay="100">
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Konten Terkait</h2>
                <div class="mt-5 grid gap-3">
                    @forelse($relatedItems as $item)
                        <a href="{{ $item['href'] }}" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-700 transition hover:border-violet-200 hover:text-[var(--brand-primary)]">
                            {{ $item['title'] }}
                        </a>
                    @empty
                        <p class="text-sm leading-7 text-slate-500">Belum ada konten terkait lainnya.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6" data-reveal data-reveal-delay="160">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Navigasi</p>
                <p class="mt-3 text-sm leading-7 text-slate-600">Gunakan halaman terkait untuk melihat informasi lain yang masih berada dalam kategori yang sama.</p>
            </div>
        </aside>
    </section>
@endsection
