@extends('layouts.public')

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <div class="max-w-3xl">
            <p class="section-kicker">{{ $pageTitle }}</p>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">{{ $pageTitle }}</h1>
            <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $pageDescription }}</p>
        </div>
    </section>

    <section class="py-8 sm:py-10" data-reveal>
        <div class="mb-5 flex flex-col items-start justify-between gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:gap-4">
            <div>
                <p class="section-kicker">Album Foto</p>
                <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Dokumentasi kegiatan sekolah</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600">Dokumentasi kegiatan yang tersusun dalam album agar mudah dijelajahi.</p>
            </div>
        </div>

        @if($albums->count() > 0)
            <div class="grid gap-5 sm:gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($albums as $album)
                    @php
                        $coverUrl = $album->cover ? asset('storage/' . $album->cover) : optional($album->foto->first())->foto_url;
                    @endphp
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white soft-card transition duration-300 hover:-translate-y-1" data-reveal data-reveal-delay="{{ 70 * ($loop->index + 1) }}">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="{{ $album->nama_album }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-slate-100 text-sm font-semibold text-slate-500">
                                    Belum ada cover
                                </div>
                            @endif
                        </div>
                        <div class="p-5 sm:p-6">
                            <div class="mb-4 flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                @if($album->tanggal)
                                    <span>{{ $album->tanggal->format('d M Y') }}</span>
                                @endif
                                <span>{{ $album->foto_count }} foto</span>
                            </div>

                            <h3 class="text-xl font-bold tracking-tight text-slate-900">{{ $album->nama_album }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                {{ \Illuminate\Support\Str::limit(trim(strip_tags($album->deskripsi ?: 'Dokumentasi kegiatan sekolah dalam bentuk album foto.')), 140) }}
                            </p>

                            <a href="{{ route('public.gallery.show', $album) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)] transition hover:gap-3">
                                Buka album
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $albums->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500">
                Album galeri belum tersedia.
            </div>
        @endif
    </section>

    <section class="grid gap-6 border-t border-slate-200 py-8 sm:gap-8 sm:py-10 lg:grid-cols-[1.05fr_0.95fr]" data-reveal>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Foto Terbaru</h2>
            <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-4 sm:grid-cols-4">
                @forelse($recentPhotos as $photo)
                    <div class="overflow-hidden rounded-[1.25rem] bg-slate-100">
                        @if($photo->foto_url)
                            <img src="{{ $photo->foto_url }}" alt="{{ $photo->judul ?: 'Foto galeri' }}" class="h-32 w-full object-cover sm:h-36">
                        @else
                            <div class="flex h-32 items-center justify-center text-xs font-semibold text-slate-400 sm:h-36">Foto</div>
                        @endif
                    </div>
                @empty
                    <p class="col-span-2 text-sm leading-7 text-slate-500 sm:col-span-4">Belum ada foto terbaru.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8" data-reveal data-reveal-delay="100">
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Video Sekolah</h2>
            <div class="mt-6 grid gap-4">
                @forelse($videos as $video)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-base font-bold text-slate-900">{{ $video->judul }}</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">
                            {{ \Illuminate\Support\Str::limit(trim(strip_tags($video->deskripsi ?: 'Dokumentasi video kegiatan sekolah.')), 100) }}
                        </p>
                        @if($video->url_youtube)
                            <a href="{{ $video->url_youtube }}" target="_blank" rel="noreferrer" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[var(--brand-primary)]">
                                Buka video
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-sm leading-7 text-slate-500">Belum ada video yang ditampilkan.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
