<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\BeritaPengumuman;
use App\Models\Ekstrakurikuler;
use App\Models\Fasilitas;
use App\Models\GaleriFoto;
use App\Models\Guru;
use App\Models\PengaturanWebsite;
use App\Models\Prestasi;
use App\Models\ProfilSekolah;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $profil = ProfilSekolah::query()->latest('id')->first();
        $settings = Cache::remember(
            'website_settings',
            now()->addMinutes(30),
            fn() => PengaturanWebsite::query()->first()
        );

        $fasilitas = Fasilitas::query()->latest('id')->take(4)->get();
        $prestasi = Prestasi::query()->latest('tahun')->latest('id')->take(4)->get();
        $galleryHighlight = Album::query()
            ->with(['foto' => fn($query) => $query->orderBy('urutan')->orderBy('id')->limit(4)])
            ->withCount('foto')
            ->where('is_active', true)
            ->latest('tanggal')
            ->latest('id')
            ->first();
        $galleryPhotos = $galleryHighlight?->foto;

        if (blank($galleryPhotos) || $galleryPhotos->isEmpty()) {
            $galleryPhotos = GaleriFoto::query()
                ->with('album')
                ->latest('id')
                ->take(4)
                ->get();
        }

        $prestasiHighlights = $prestasi
            ->filter(fn ($item) => filled($item->foto) || filled($item->sertifikat))
            ->take(4)
            ->values();
        $berita = BeritaPengumuman::query()
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();
        $ekstrakurikuler = Ekstrakurikuler::query()->orderByDesc('is_active')->latest('id')->take(4)->get();

        $stats = [
            [
                'label' => 'Tenaga Pendidik',
                'value' => Guru::query()->count(),
            ],
            [
                'label' => 'Fasilitas Sekolah',
                'value' => Fasilitas::query()->count(),
            ],
            [
                'label' => 'Ekstrakurikuler',
                'value' => Ekstrakurikuler::query()->count(),
            ],
            [
                'label' => 'Prestasi Tercatat',
                'value' => Prestasi::query()->count(),
            ],
        ];

        return view('welcome', compact(
            'profil',
            'settings',
            'fasilitas',
            'prestasi',
            'galleryHighlight',
            'galleryPhotos',
            'prestasiHighlights',
            'berita',
            'ekstrakurikuler',
            'stats'
        ));
    }
}
