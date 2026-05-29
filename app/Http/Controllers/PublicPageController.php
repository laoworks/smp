<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Album;
use App\Models\BeritaPengumuman;
use App\Models\Ekstrakurikuler;
use App\Models\Fasilitas;
use App\Models\GaleriFoto;
use App\Models\GaleriVideo;
use App\Models\GelombangPpdb;
use App\Models\Guru;
use App\Models\KalenderAkademik;
use App\Models\Pendaftar;
use App\Models\PesanKontak;
use App\Models\Prestasi;
use App\Models\ProfilSekolah;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function search(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));
        $results = collect();

        if ($keyword !== '') {
            $likeKeyword = '%' . $keyword . '%';

            $newsResults = BeritaPengumuman::query()
                ->where('is_published', true)
                ->where(function ($query) use ($likeKeyword) {
                    $query
                        ->where('judul', 'like', $likeKeyword)
                        ->orWhere('konten', 'like', $likeKeyword)
                        ->orWhere('jenis', 'like', $likeKeyword)
                        ->orWhere('penulis', 'like', $likeKeyword);
                })
                ->latest('published_at')
                ->take(8)
                ->get()
                ->map(fn(BeritaPengumuman $item) => [
                    'section' => 'Berita',
                    'title' => $item->judul,
                    'excerpt' => Str::limit(trim(strip_tags($item->konten ?: 'Informasi terbaru sekolah.')), 180),
                    'image' => $this->imageUrl($item->gambar_utama),
                    'href' => route('public.news.show', $item),
                    'meta' => array_values(array_filter([$item->jenis, $item->tanggal_posting])),
                ]);

            $facilityResults = Fasilitas::query()
                ->where(function ($query) use ($likeKeyword) {
                    $query
                        ->where('nama_fasilitas', 'like', $likeKeyword)
                        ->orWhere('deskripsi', 'like', $likeKeyword)
                        ->orWhere('kondisi', 'like', $likeKeyword)
                        ->orWhere('status', 'like', $likeKeyword);
                })
                ->latest('id')
                ->take(6)
                ->get()
                ->map(fn(Fasilitas $item) => [
                    'section' => 'Fasilitas',
                    'title' => $item->nama_fasilitas,
                    'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Informasi fasilitas sekolah.')), 180),
                    'image' => $this->imageUrl($item->gambar),
                    'href' => route('public.facilities.show', $item),
                    'meta' => array_values(array_filter([$item->kondisi, $item->status])),
                ]);

            $achievementResults = Prestasi::query()
                ->where(function ($query) use ($likeKeyword) {
                    $query
                        ->where('judul', 'like', $likeKeyword)
                        ->orWhere('deskripsi', 'like', $likeKeyword)
                        ->orWhere('jenis', 'like', $likeKeyword)
                        ->orWhere('tingkat', 'like', $likeKeyword)
                        ->orWhere('peserta_nama', 'like', $likeKeyword);
                })
                ->latest('tahun')
                ->latest('id')
                ->take(6)
                ->get()
                ->map(fn(Prestasi $item) => [
                    'section' => 'Prestasi',
                    'title' => $item->judul,
                    'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Informasi prestasi sekolah.')), 180),
                    'image' => $this->imageUrl($item->foto ?: $item->sertifikat),
                    'href' => route('public.achievements.show', $item),
                    'meta' => array_values(array_filter([$item->jenis, $item->tingkat, $item->tahun])),
                ]);

            $teacherResults = Guru::query()
                ->where(function ($query) use ($likeKeyword) {
                    $query
                        ->where('nama_lengkap', 'like', $likeKeyword)
                        ->orWhere('mata_pelajaran', 'like', $likeKeyword)
                        ->orWhere('jabatan', 'like', $likeKeyword)
                        ->orWhere('pendidikan_terakhir', 'like', $likeKeyword);
                })
                ->orderByDesc('is_active')
                ->orderBy('nama_lengkap')
                ->take(6)
                ->get()
                ->map(fn(Guru $item) => [
                    'section' => 'Guru',
                    'title' => trim(collect([$item->gelar_depan, $item->nama_lengkap, $item->gelar_belakang])->filter()->implode(' ')),
                    'excerpt' => Str::limit(trim(strip_tags($item->mata_pelajaran ?: $item->jabatan ?: 'Profil tenaga pendidik sekolah.')), 180),
                    'image' => $this->imageUrl($item->foto),
                    'href' => route('public.teachers.show', $item),
                    'meta' => array_values(array_filter([$item->mata_pelajaran, $item->jabatan, $item->status])),
                ]);

            $extracurricularResults = Ekstrakurikuler::query()
                ->where(function ($query) use ($likeKeyword) {
                    $query
                        ->where('nama_ekskul', 'like', $likeKeyword)
                        ->orWhere('deskripsi', 'like', $likeKeyword)
                        ->orWhere('prestasi', 'like', $likeKeyword)
                        ->orWhere('tempat_latihan', 'like', $likeKeyword);
                })
                ->orderByDesc('is_active')
                ->latest('id')
                ->take(6)
                ->get()
                ->map(fn(Ekstrakurikuler $item) => [
                    'section' => 'Ekstrakurikuler',
                    'title' => $item->nama_ekskul,
                    'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Informasi kegiatan ekstrakurikuler sekolah.')), 180),
                    'image' => $this->imageUrl($item->gambar),
                    'href' => route('public.extracurriculars.show', $item),
                    'meta' => array_values(array_filter([$item->jadwal_latihan, $item->tempat_latihan])),
                ]);

            $alumniResults = Alumni::query()
                ->where(function ($query) use ($likeKeyword) {
                    $query
                        ->where('nama_lengkap', 'like', $likeKeyword)
                        ->orWhere('kisah_sukses', 'like', $likeKeyword)
                        ->orWhere('prestasi_alumni', 'like', $likeKeyword)
                        ->orWhere('pekerjaan', 'like', $likeKeyword)
                        ->orWhere('universitas', 'like', $likeKeyword);
                })
                ->orderByDesc('is_featured')
                ->orderByDesc('is_verified')
                ->latest('tahun_lulus')
                ->take(6)
                ->get()
                ->map(fn(Alumni $item) => [
                    'section' => 'Alumni',
                    'title' => $item->nama_lengkap,
                    'excerpt' => Str::limit(trim(strip_tags($item->kisah_sukses ?: $item->prestasi_alumni ?: $item->pekerjaan ?: 'Profil alumni sekolah.')), 180),
                    'image' => $this->imageUrl($item->foto),
                    'href' => route('public.alumni.show', $item),
                    'meta' => array_values(array_filter([$item->tahun_lulus ? 'Lulus ' . $item->tahun_lulus : null, $item->pekerjaan ?: $item->universitas])),
                ]);

            $calendarResults = KalenderAkademik::query()
                ->where('is_published', true)
                ->where(function ($query) use ($likeKeyword) {
                    $query
                        ->where('judul_kegiatan', 'like', $likeKeyword)
                        ->orWhere('deskripsi', 'like', $likeKeyword)
                        ->orWhere('jenis', 'like', $likeKeyword)
                        ->orWhere('tempat', 'like', $likeKeyword);
                })
                ->orderBy('tanggal_mulai')
                ->take(6)
                ->get()
                ->map(fn(KalenderAkademik $item) => [
                    'section' => 'Kalender Akademik',
                    'title' => $item->judul_kegiatan,
                    'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Agenda kegiatan sekolah.')), 180),
                    'image' => null,
                    'href' => route('public.calendar.show', $item),
                    'meta' => array_values(array_filter([$item->jenis, $item->tanggal_mulai?->format('d M Y'), $item->tempat])),
                ]);

            $results = $newsResults
                ->concat($facilityResults)
                ->concat($achievementResults)
                ->concat($teacherResults)
                ->concat($extracurricularResults)
                ->concat($alumniResults)
                ->concat($calendarResults)
                ->values();
        }

        return view('public.search', [
            'pageTitle' => 'Pencarian',
            'pageDescription' => 'Cari berita, guru, fasilitas, prestasi, dan konten publik sekolah lainnya dari satu tempat.',
            'keyword' => $keyword,
            'results' => $results,
            'resultCount' => $results->count(),
            'sectionCounts' => $results->groupBy('section')->map->count()->sortDesc(),
        ]);
    }

    public function contact(): View
    {
        $profil = ProfilSekolah::query()->latest('id')->first();
        $fullAddress = collect([
            $profil?->alamat,
            $profil?->desa,
            $profil?->kecamatan,
            $profil?->kabupaten,
            $profil?->provinsi,
            $profil?->kode_pos,
        ])->filter(fn($value) => filled($value))->implode(', ');

        $mapQuery = $fullAddress !== ''
            ? $fullAddress
            : ($profil?->nama_sekolah ?: 'Lokasi sekolah');

        return view('public.contact', [
            'profil' => $profil,
            'pageTitle' => 'Kontak',
            'pageDescription' => 'Lihat informasi kontak sekolah secara lengkap beserta lokasi GPS yang dapat langsung dibuka di Google Maps.',
            'fullAddress' => $fullAddress,
            'mapEmbedUrl' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3980.8480028331683!2d126.72497447497445!3d-3.8427875961309956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d72dda65ce588af%3A0x4b2ba53982d11fbb!2sSMP%20NEGERI%2001%20NAMROLE!5e0!3m2!1sid!2sid!4v1780077575173!5m2!1sid!2sid',
            'mapUrl' => 'https://www.google.com/maps/place/SMP+NEGERI+01+NAMROLE/@-3.8427876,126.7249745,17z',
            'websiteUrl' => filled($profil?->website)
                ? (\Illuminate\Support\Str::startsWith($profil->website, ['http://', 'https://'])
                    ? $profil->website
                    : 'https://' . ltrim($profil->website, '/'))
                : null,
        ]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'subjek' => ['nullable', 'string', 'max:200'],
            'pesan' => ['required', 'string'],
        ]);

        PesanKontak::query()->create($validated);

        return redirect()
            ->route('public.contact')
            ->with('success', 'Pesan berhasil dikirim. Silakan tunggu balasan dari pihak sekolah.');
    }

    public function profile(): View
    {
        $profil = ProfilSekolah::query()->latest('id')->first();

        return view('public.profile', [
            'profil' => $profil,
            'pageTitle' => 'Profil Sekolah',
            'pageDescription' => 'Lihat ringkasan identitas, sejarah, visi, misi, dan informasi utama sekolah.',
        ]);
    }

    public function structure(): View
    {
        return view('public.structure', [
            'pageTitle' => 'Struktur Organisasi',
            'pageDescription' => 'Lihat struktur organisasi sekolah yang aktif beserta penjelasan singkatnya.',
            'structures' => StrukturOrganisasi::query()
                ->orderByDesc('is_active')
                ->latest('tahun')
                ->latest('id')
                ->get(),
        ]);
    }

    public function facilities(): View
    {
        $items = $this->transformPaginator(
            Fasilitas::query()->latest('id')->paginate(9),
            fn(Fasilitas $item) => [
                'title' => $item->nama_fasilitas,
                'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Fasilitas sekolah yang disiapkan untuk mendukung proses belajar dan kegiatan siswa.')), 150),
                'image' => $this->imageUrl($item->gambar),
                'href' => route('public.facilities.show', $item),
                'meta' => array_values(array_filter([
                    filled($item->jumlah) ? $item->jumlah . ' unit' : null,
                    $item->kondisi,
                    $item->status,
                ])),
            ]
        );

        return view('public.listing', [
            'pageTitle' => 'Fasilitas',
            'pageDescription' => 'Telusuri sarana belajar dan fasilitas sekolah yang menunjang kegiatan akademik maupun non-akademik.',
            'cards' => $items,
            'emptyText' => 'Data fasilitas belum tersedia.',
        ]);
    }

    public function facility(Fasilitas $fasilitas): View
    {
        $relatedItems = Fasilitas::query()
            ->whereKeyNot($fasilitas->getKey())
            ->latest('id')
            ->take(3)
            ->get()
            ->map(fn(Fasilitas $item) => [
                'title' => $item->nama_fasilitas,
                'href' => route('public.facilities.show', $item),
            ]);

        return view('public.detail', [
            'sectionLabel' => 'Fasilitas',
            'title' => $fasilitas->nama_fasilitas,
            'description' => Str::limit(trim(strip_tags($fasilitas->deskripsi ?: 'Informasi fasilitas sekolah.')), 180),
            'image' => $this->imageUrl($fasilitas->gambar),
            'backUrl' => route('public.facilities.index'),
            'backLabel' => 'Kembali ke fasilitas',
            'meta' => array_values(array_filter([
                ['label' => 'Jumlah', 'value' => filled($fasilitas->jumlah) ? $fasilitas->jumlah : '-'],
                ['label' => 'Kondisi', 'value' => $fasilitas->kondisi ?: '-'],
                ['label' => 'Status', 'value' => $fasilitas->status ?: '-'],
            ])),
            'contentSections' => [
                [
                    'title' => 'Deskripsi',
                    'html' => $this->textToHtml($fasilitas->deskripsi, 'Deskripsi fasilitas belum ditambahkan.'),
                ],
            ],
            'relatedItems' => $relatedItems,
        ]);
    }

    public function extracurriculars(): View
    {
        $items = $this->transformPaginator(
            Ekstrakurikuler::query()
                ->with('pembina')
                ->orderByDesc('is_active')
                ->latest('id')
                ->paginate(9),
            fn(Ekstrakurikuler $item) => [
                'title' => $item->nama_ekskul,
                'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Kegiatan pengembangan minat, bakat, dan karakter siswa.')), 150),
                'image' => $this->imageUrl($item->gambar),
                'href' => route('public.extracurriculars.show', $item),
                'meta' => array_values(array_filter([
                    $item->pembina?->nama_lengkap,
                    $item->jadwal_latihan,
                    $item->is_active ? 'Aktif' : 'Nonaktif',
                ])),
            ]
        );

        return view('public.listing', [
            'pageTitle' => 'Ekstrakurikuler',
            'pageDescription' => 'Jelajahi kegiatan ekstrakurikuler yang aktif untuk mendukung bakat, minat, dan kedisiplinan siswa.',
            'cards' => $items,
            'emptyText' => 'Data ekstrakurikuler belum tersedia.',
        ]);
    }

    public function extracurricular(Ekstrakurikuler $ekstrakurikuler): View
    {
        $ekstrakurikuler->load('pembina');

        $relatedItems = Ekstrakurikuler::query()
            ->whereKeyNot($ekstrakurikuler->getKey())
            ->orderByDesc('is_active')
            ->latest('id')
            ->take(3)
            ->get()
            ->map(fn(Ekstrakurikuler $item) => [
                'title' => $item->nama_ekskul,
                'href' => route('public.extracurriculars.show', $item),
            ]);

        return view('public.detail', [
            'sectionLabel' => 'Ekstrakurikuler',
            'title' => $ekstrakurikuler->nama_ekskul,
            'description' => Str::limit(trim(strip_tags($ekstrakurikuler->deskripsi ?: 'Informasi kegiatan ekstrakurikuler sekolah.')), 180),
            'image' => $this->imageUrl($ekstrakurikuler->gambar),
            'backUrl' => route('public.extracurriculars.index'),
            'backLabel' => 'Kembali ke ekstrakurikuler',
            'meta' => array_values(array_filter([
                ['label' => 'Pembina', 'value' => $ekstrakurikuler->pembina?->nama_lengkap ?: '-'],
                ['label' => 'Jadwal', 'value' => $ekstrakurikuler->jadwal_latihan ?: '-'],
                ['label' => 'Tempat', 'value' => $ekstrakurikuler->tempat_latihan ?: '-'],
                ['label' => 'Kuota', 'value' => filled($ekstrakurikuler->kuota) ? $ekstrakurikuler->kuota : '-'],
            ])),
            'contentSections' => [
                [
                    'title' => 'Deskripsi Kegiatan',
                    'html' => $this->textToHtml($ekstrakurikuler->deskripsi, 'Deskripsi kegiatan belum ditambahkan.'),
                ],
                [
                    'title' => 'Prestasi',
                    'html' => $this->textToHtml($ekstrakurikuler->prestasi, 'Prestasi ekstrakurikuler belum ditambahkan.'),
                ],
            ],
            'relatedItems' => $relatedItems,
        ]);
    }

    public function achievements(): View
    {
        $items = $this->transformPaginator(
            Prestasi::query()->latest('tahun')->latest('id')->paginate(9),
            fn(Prestasi $item) => [
                'title' => $item->judul,
                'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Informasi pencapaian dan prestasi sekolah.')), 150),
                'image' => $this->imageUrl($item->foto ?: $item->sertifikat),
                'href' => route('public.achievements.show', $item),
                'meta' => array_values(array_filter([
                    $item->tingkat,
                    $item->juara,
                    $item->tahun,
                ])),
            ]
        );

        return view('public.listing', [
            'pageTitle' => 'Prestasi',
            'pageDescription' => 'Lihat capaian sekolah dan siswa dari berbagai lomba, kegiatan, maupun ajang akademik lainnya.',
            'cards' => $items,
            'emptyText' => 'Data prestasi belum tersedia.',
        ]);
    }

    public function alumni(): View
    {
        $items = $this->transformPaginator(
            Alumni::query()
                ->orderByDesc('is_featured')
                ->orderByDesc('is_verified')
                ->latest('tahun_lulus')
                ->paginate(9),
            fn(Alumni $item) => [
                'title' => $item->nama_lengkap,
                'excerpt' => Str::limit(trim(strip_tags($item->kisah_sukses ?: $item->prestasi_alumni ?: $item->pekerjaan ?: 'Profil alumni sekolah.')), 150),
                'image' => $this->imageUrl($item->foto),
                'href' => route('public.alumni.show', $item),
                'meta' => array_values(array_filter([
                    $item->tahun_lulus ? 'Lulus ' . $item->tahun_lulus : null,
                    $item->pekerjaan ?: $item->universitas,
                ])),
            ]
        );

        return view('public.listing', [
            'pageTitle' => 'Alumni',
            'pageDescription' => 'Kenali jejak alumni sekolah, mulai dari pendidikan lanjutan hingga perjalanan karier mereka.',
            'cards' => $items,
            'emptyText' => 'Data alumni belum tersedia.',
        ]);
    }

    public function alumnus(Alumni $alumni): View
    {
        $relatedItems = Alumni::query()
            ->whereKeyNot($alumni->getKey())
            ->orderByDesc('is_featured')
            ->orderByDesc('is_verified')
            ->latest('tahun_lulus')
            ->take(3)
            ->get()
            ->map(fn(Alumni $item) => [
                'title' => $item->nama_lengkap,
                'href' => route('public.alumni.show', $item),
            ]);

        return view('public.detail', [
            'sectionLabel' => 'Alumni',
            'title' => $alumni->nama_lengkap,
            'description' => Str::limit(trim(strip_tags($alumni->kisah_sukses ?: $alumni->prestasi_alumni ?: $alumni->pekerjaan ?: 'Profil alumni sekolah.')), 180),
            'image' => $this->imageUrl($alumni->foto),
            'backUrl' => route('public.alumni.index'),
            'backLabel' => 'Kembali ke alumni',
            'meta' => array_values(array_filter([
                ['label' => 'Tahun Lulus', 'value' => $alumni->tahun_lulus ?: '-'],
                ['label' => 'Universitas', 'value' => $alumni->universitas ?: '-'],
                ['label' => 'Program Studi', 'value' => $alumni->jurusan_kuliah ?: '-'],
                ['label' => 'Pekerjaan', 'value' => $alumni->pekerjaan ?: '-'],
                ['label' => 'Perusahaan', 'value' => $alumni->perusahaan ?: '-'],
                ['label' => 'Posisi', 'value' => $alumni->posisi ?: '-'],
                ['label' => 'Email', 'value' => $alumni->email ?: '-'],
            ])),
            'contentSections' => [
                [
                    'title' => 'Kisah Alumni',
                    'html' => $this->textToHtml($alumni->kisah_sukses, 'Kisah alumni belum ditambahkan.'),
                ],
                [
                    'title' => 'Prestasi Alumni',
                    'html' => $this->textToHtml($alumni->prestasi_alumni, 'Prestasi alumni belum ditambahkan.'),
                ],
            ],
            'relatedItems' => $relatedItems,
        ]);
    }

    public function calendars(): View
    {
        $items = $this->transformPaginator(
            KalenderAkademik::query()
                ->where('is_published', true)
                ->orderBy('tanggal_mulai')
                ->orderBy('id')
                ->paginate(10),
            fn(KalenderAkademik $item) => [
                'title' => $item->judul_kegiatan,
                'excerpt' => Str::limit(trim(strip_tags($item->deskripsi ?: 'Agenda kegiatan akademik sekolah.')), 150),
                'image' => null,
                'href' => route('public.calendar.show', $item),
                'meta' => array_values(array_filter([
                    $item->jenis,
                    $item->tanggal_mulai?->format('d M Y'),
                    $item->tempat,
                ])),
            ]
        );

        return view('public.listing', [
            'pageTitle' => 'Kalender Akademik',
            'pageDescription' => 'Pantau agenda kegiatan akademik, jadwal penting, dan momen sekolah yang sudah dipublikasikan.',
            'cards' => $items,
            'emptyText' => 'Kalender akademik belum tersedia.',
        ]);
    }

    public function calendar(KalenderAkademik $kalenderAkademik): View
    {
        $relatedItems = KalenderAkademik::query()
            ->where('is_published', true)
            ->whereKeyNot($kalenderAkademik->getKey())
            ->orderBy('tanggal_mulai')
            ->orderBy('id')
            ->take(3)
            ->get()
            ->map(fn(KalenderAkademik $item) => [
                'title' => $item->judul_kegiatan,
                'href' => route('public.calendar.show', $item),
            ]);

        return view('public.detail', [
            'sectionLabel' => 'Kalender Akademik',
            'title' => $kalenderAkademik->judul_kegiatan,
            'description' => Str::limit(trim(strip_tags($kalenderAkademik->deskripsi ?: 'Agenda kegiatan sekolah.')), 180),
            'image' => null,
            'backUrl' => route('public.calendar.index'),
            'backLabel' => 'Kembali ke kalender akademik',
            'meta' => array_values(array_filter([
                ['label' => 'Jenis', 'value' => $kalenderAkademik->jenis ?: '-'],
                ['label' => 'Mulai', 'value' => $kalenderAkademik->tanggal_mulai?->format('d M Y') ?: '-'],
                ['label' => 'Selesai', 'value' => $kalenderAkademik->tanggal_selesai?->format('d M Y') ?: '-'],
                ['label' => 'Waktu', 'value' => $kalenderAkademik->waktu ?: '-'],
                ['label' => 'Tempat', 'value' => $kalenderAkademik->tempat ?: '-'],
                ['label' => 'Target', 'value' => $kalenderAkademik->target_audience ?: '-'],
            ])),
            'contentSections' => [
                [
                    'title' => 'Deskripsi Kegiatan',
                    'html' => $this->textToHtml($kalenderAkademik->deskripsi, 'Deskripsi kegiatan belum ditambahkan.'),
                ],
            ],
            'relatedItems' => $relatedItems,
        ]);
    }

    public function achievement(Prestasi $prestasi): View
    {
        $relatedItems = Prestasi::query()
            ->whereKeyNot($prestasi->getKey())
            ->latest('tahun')
            ->latest('id')
            ->take(3)
            ->get()
            ->map(fn(Prestasi $item) => [
                'title' => $item->judul,
                'href' => route('public.achievements.show', $item),
            ]);

        return view('public.detail', [
            'sectionLabel' => 'Prestasi',
            'title' => $prestasi->judul,
            'description' => Str::limit(trim(strip_tags($prestasi->deskripsi ?: 'Informasi prestasi sekolah.')), 180),
            'image' => $this->imageUrl($prestasi->foto ?: $prestasi->sertifikat),
            'backUrl' => route('public.achievements.index'),
            'backLabel' => 'Kembali ke prestasi',
            'meta' => array_values(array_filter([
                ['label' => 'Jenis', 'value' => $prestasi->jenis ?: '-'],
                ['label' => 'Tingkat', 'value' => $prestasi->tingkat ?: '-'],
                ['label' => 'Juara', 'value' => $prestasi->juara ?: '-'],
                ['label' => 'Tahun', 'value' => $prestasi->tahun ?: '-'],
                ['label' => 'Peserta', 'value' => $prestasi->peserta_nama ?: '-'],
                ['label' => 'Kelas', 'value' => $prestasi->peserta_kelas ?: '-'],
            ])),
            'contentSections' => [
                [
                    'title' => 'Deskripsi Prestasi',
                    'html' => $this->textToHtml($prestasi->deskripsi, 'Deskripsi prestasi belum ditambahkan.'),
                ],
            ],
            'relatedItems' => $relatedItems,
        ]);
    }

    public function teachers(): View
    {
        $items = $this->transformPaginator(
            Guru::query()->orderByDesc('is_active')->orderBy('nama_lengkap')->paginate(12),
            fn(Guru $item) => [
                'title' => trim(collect([$item->gelar_depan, $item->nama_lengkap, $item->gelar_belakang])->filter()->implode(' ')),
                'excerpt' => Str::limit(trim(strip_tags($item->mata_pelajaran ?: $item->jabatan ?: 'Tenaga pendidik sekolah.')), 120),
                'image' => $this->imageUrl($item->foto),
                'href' => route('public.teachers.show', $item),
                'meta' => array_values(array_filter([
                    $item->mata_pelajaran,
                    $item->jabatan,
                    $item->status,
                ])),
            ]
        );

        return view('public.listing', [
            'pageTitle' => 'Guru',
            'pageDescription' => 'Kenali tenaga pendidik sekolah beserta bidang ajar dan peran yang mereka jalankan.',
            'cards' => $items,
            'emptyText' => 'Data guru belum tersedia.',
        ]);
    }

    public function teacher(Guru $guru): View
    {
        $relatedItems = Guru::query()
            ->whereKeyNot($guru->getKey())
            ->orderByDesc('is_active')
            ->orderBy('nama_lengkap')
            ->take(3)
            ->get()
            ->map(fn(Guru $item) => [
                'title' => $item->nama_lengkap,
                'href' => route('public.teachers.show', $item),
            ]);

        return view('public.detail', [
            'sectionLabel' => 'Guru',
            'title' => trim(collect([$guru->gelar_depan, $guru->nama_lengkap, $guru->gelar_belakang])->filter()->implode(' ')),
            'description' => Str::limit(trim(strip_tags($guru->mata_pelajaran ?: $guru->jabatan ?: 'Profil tenaga pendidik sekolah.')), 180),
            'image' => $this->imageUrl($guru->foto),
            'backUrl' => route('public.teachers.index'),
            'backLabel' => 'Kembali ke daftar guru',
            'meta' => array_values(array_filter([
                ['label' => 'Mata Pelajaran', 'value' => $guru->mata_pelajaran ?: '-'],
                ['label' => 'Jabatan', 'value' => $guru->jabatan ?: '-'],
                ['label' => 'Status', 'value' => $guru->status ?: '-'],
                ['label' => 'Pendidikan', 'value' => $guru->pendidikan_terakhir ?: '-'],
                ['label' => 'Universitas', 'value' => $guru->universitas ?: '-'],
                ['label' => 'Telepon', 'value' => $guru->telepon ?: '-'],
                ['label' => 'Email', 'value' => $guru->email ?: '-'],
            ])),
            'contentSections' => [
                [
                    'title' => 'Profil Singkat',
                    'html' => $this->textToHtml(
                        collect([
                            $guru->mata_pelajaran ? 'Mengampu mata pelajaran ' . $guru->mata_pelajaran . '.' : null,
                            $guru->jabatan ? 'Menjalankan peran sebagai ' . $guru->jabatan . '.' : null,
                            $guru->alamat ? 'Domisili: ' . $guru->alamat . '.' : null,
                        ])->filter()->implode("\n"),
                        'Informasi profil guru belum ditambahkan.'
                    ),
                ],
            ],
            'relatedItems' => $relatedItems,
        ]);
    }

    public function news(): View
    {
        $items = $this->transformPaginator(
            BeritaPengumuman::query()
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(9),
            fn(BeritaPengumuman $item) => [
                'title' => $item->judul,
                'excerpt' => Str::limit(trim(strip_tags($item->konten ?: 'Informasi terbaru dari sekolah.')), 150),
                'image' => $this->imageUrl($item->gambar_utama),
                'href' => route('public.news.show', $item),
                'meta' => array_values(array_filter([
                    $item->jenis,
                    $item->tanggal_posting,
                    $item->is_urgent ? 'Penting' : null,
                ])),
            ]
        );

        return view('public.listing', [
            'pageTitle' => 'Berita dan Pengumuman',
            'pageDescription' => 'Ikuti informasi terbaru, pengumuman penting, dan berbagai kabar kegiatan sekolah.',
            'cards' => $items,
            'emptyText' => 'Belum ada berita yang dipublikasikan.',
        ]);
    }

    public function newsShow(BeritaPengumuman $beritaPengumuman): View
    {
        $relatedItems = BeritaPengumuman::query()
            ->where('is_published', true)
            ->whereKeyNot($beritaPengumuman->getKey())
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(fn(BeritaPengumuman $item) => [
                'title' => $item->judul,
                'href' => route('public.news.show', $item),
            ]);

        return view('public.detail', [
            'sectionLabel' => 'Berita dan Pengumuman',
            'title' => $beritaPengumuman->judul,
            'description' => Str::limit(trim(strip_tags($beritaPengumuman->konten ?: 'Informasi sekolah.')), 180),
            'image' => $this->imageUrl($beritaPengumuman->gambar_utama),
            'backUrl' => route('public.news.index'),
            'backLabel' => 'Kembali ke berita',
            'meta' => array_values(array_filter([
                ['label' => 'Jenis', 'value' => $beritaPengumuman->jenis ?: '-'],
                ['label' => 'Tanggal', 'value' => $beritaPengumuman->tanggal_posting ?: '-'],
                ['label' => 'Penulis', 'value' => $beritaPengumuman->penulis ?: '-'],
                ['label' => 'Views', 'value' => filled($beritaPengumuman->views) ? $beritaPengumuman->views : '-'],
            ])),
            'contentSections' => [
                [
                    'title' => 'Isi Informasi',
                    'html' => $this->textToHtml($beritaPengumuman->konten, 'Konten berita belum ditambahkan.'),
                ],
            ],
            'relatedItems' => $relatedItems,
        ]);
    }

    public function gallery(): View
    {
        $albums = Album::query()
            ->withCount('foto')
            ->with(['foto' => fn($query) => $query->orderBy('urutan')->limit(4)])
            ->where('is_active', true)
            ->latest('tanggal')
            ->paginate(8);

        $videos = GaleriVideo::query()
            ->where('is_active', true)
            ->latest('tanggal')
            ->take(4)
            ->get();

        return view('public.gallery', [
            'pageTitle' => 'Galeri',
            'pageDescription' => 'Lihat dokumentasi kegiatan sekolah melalui album foto dan video yang tersusun rapi.',
            'albums' => $albums,
            'videos' => $videos,
            'recentPhotos' => GaleriFoto::query()->latest('id')->take(8)->get(),
        ]);
    }

    public function galleryShow(Album $album): View
    {
        $album->load(['foto' => fn($query) => $query->orderBy('urutan')->orderBy('id')]);

        $relatedAlbums = Album::query()
            ->where('is_active', true)
            ->whereKeyNot($album->getKey())
            ->latest('tanggal')
            ->take(3)
            ->get();

        return view('public.gallery-show', [
            'album' => $album,
            'pageTitle' => $album->nama_album,
            'pageDescription' => Str::limit(trim(strip_tags($album->deskripsi ?: 'Dokumentasi album kegiatan sekolah.')), 180),
            'relatedAlbums' => $relatedAlbums,
        ]);
    }

    public function ppdb(): View
    {
        return view('public.ppdb', [
            'pageTitle' => 'PPDB',
            'pageDescription' => 'Informasi gelombang pendaftaran peserta didik baru yang tersedia saat ini.',
            'activeWaves' => GelombangPpdb::query()
                ->where('is_active', true)
                ->orderBy('periode_mulai')
                ->get(),
            'otherWaves' => GelombangPpdb::query()
                ->where('is_active', false)
                ->latest('periode_mulai')
                ->take(6)
                ->get(),
        ]);
    }

    public function storePpdb(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'gelombang_id' => ['required', 'exists:gelombang_ppdbs,id'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'nik' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama' => ['nullable', 'string', 'max:20'],
            'anak_ke' => ['nullable', 'integer', 'min:1', 'max:20'],
            'jumlah_saudara' => ['nullable', 'integer', 'min:0', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'rt_rw' => ['nullable', 'string', 'max:20'],
            'kelurahan' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kota' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'asal_sekolah' => ['nullable', 'string', 'max:100'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'tahun_lulus' => ['nullable', 'integer', 'between:1901,2155'],
            'ijazah_number' => ['nullable', 'string', 'max:50'],
            'ayah_nama' => ['nullable', 'string', 'max:100'],
            'ayah_pekerjaan' => ['nullable', 'string', 'max:100'],
            'ayah_pendidikan' => ['nullable', 'string', 'max:50'],
            'ayah_no_hp' => ['nullable', 'string', 'max:20'],
            'ibu_nama' => ['nullable', 'string', 'max:100'],
            'ibu_pekerjaan' => ['nullable', 'string', 'max:100'],
            'ibu_pendidikan' => ['nullable', 'string', 'max:50'],
            'ibu_no_hp' => ['nullable', 'string', 'max:20'],
            'wali_nama' => ['nullable', 'string', 'max:100'],
            'wali_pekerjaan' => ['nullable', 'string', 'max:100'],
            'wali_hubungan' => ['nullable', 'string', 'max:50'],
            'foto_kk' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'foto_akte' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'foto_ijazah' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'foto_nilai' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'foto_sertifikat' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $validated['no_pendaftaran'] = $this->generateRegistrationNumber();
        $validated['tanggal_daftar'] = now();
        $validated['status_verifikasi'] = 'pending';

        foreach (['foto_kk', 'foto_akte', 'foto_ijazah', 'foto_nilai', 'foto_sertifikat'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $validated[$fileField] = $request->file($fileField)->store('ppdb', 'public');
            }
        }

        Pendaftar::query()->create($validated);

        return redirect()
            ->route('public.ppdb.index')
            ->with('success', 'Pendaftaran berhasil dikirim. Silakan tunggu proses verifikasi dari sekolah.');
    }

    protected function imageUrl(?string $path): ?string
    {
        return filled($path) ? asset('storage/' . $path) : null;
    }

    protected function generateRegistrationNumber(): string
    {
        do {
            $number = 'PPDB-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
        } while (Pendaftar::query()->where('no_pendaftaran', $number)->exists());

        return $number;
    }

    protected function textToHtml(?string $value, string $fallback): string
    {
        $content = trim((string) ($value ?: $fallback));

        if ($content === '') {
            return '<p>-</p>';
        }

        if (str_contains($content, '<')) {
            return $content;
        }

        return collect(preg_split('/\R+/', $content))
            ->filter()
            ->map(fn(string $paragraph) => '<p>' . e($paragraph) . '</p>')
            ->implode('');
    }

    protected function transformPaginator(LengthAwarePaginator $paginator, callable $mapper): LengthAwarePaginator
    {
        $paginator->setCollection(
            $paginator->getCollection()->map($mapper)
        );

        return $paginator;
    }
}
