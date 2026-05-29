<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\BeritaPengumuman;
use App\Models\GaleriFoto;
use App\Models\GaleriVideo;
use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\Pendaftar;
use App\Models\PesanKontak;
use App\Models\Slider;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();

        $totalPendaftar = Pendaftar::count();
        $pendingPendaftar = Pendaftar::where('status_verifikasi', 'pending')->count();
        $verifiedPendaftar = Pendaftar::whereIn('status_verifikasi', ['verifikasi', 'diterima'])->count();

        $totalGuru = Guru::count();
        $activeGuru = Guru::where('is_active', true)->count();

        $totalBerita = BeritaPengumuman::count();
        $publishedBerita = BeritaPengumuman::where('is_published', true)->count();

        $ppdbStatuses = [
            'Pending' => Pendaftar::where('status_verifikasi', 'pending')->count(),
            'Terverifikasi' => Pendaftar::where('status_verifikasi', 'verifikasi')->count(),
            'Diterima' => Pendaftar::where('status_verifikasi', 'diterima')->count(),
            'Cadangan' => Pendaftar::where('status_verifikasi', 'cadangan')->count(),
            'Ditolak' => Pendaftar::where('status_verifikasi', 'ditolak')->count(),
        ];

        $contentStats = [
            'Berita Publish' => $publishedBerita,
            'Draft Berita' => max($totalBerita - $publishedBerita, 0),
            'Album' => Album::count(),
            'Foto' => GaleriFoto::count(),
            'Video' => GaleriVideo::count(),
            'Slider' => Slider::count(),
        ];

        $messageStats = [
            'Belum Dibaca' => PesanKontak::where('is_read', false)->count(),
            'Sudah Dibaca' => PesanKontak::where('is_read', true)->count(),
            'Sudah Dibalas' => PesanKontak::where('is_replied', true)->count(),
        ];

        $recentActivities = LogAktivitas::with('user:id,name')
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            [
                'label' => 'Total User',
                'value' => $totalUsers,
                'description' => $activeUsers . ' user aktif',
                'icon_bg' => 'bg-indigo-100',
                'icon_text' => 'text-indigo-600',
            ],
            [
                'label' => 'Total Pendaftar',
                'value' => $totalPendaftar,
                'description' => $pendingPendaftar . ' menunggu verifikasi',
                'icon_bg' => 'bg-emerald-100',
                'icon_text' => 'text-emerald-600',
            ],
            [
                'label' => 'Data Guru',
                'value' => $totalGuru,
                'description' => $activeGuru . ' guru aktif',
                'icon_bg' => 'bg-amber-100',
                'icon_text' => 'text-amber-600',
            ],
            [
                'label' => 'Berita Publish',
                'value' => $publishedBerita,
                'description' => $totalBerita . ' total berita',
                'icon_bg' => 'bg-rose-100',
                'icon_text' => 'text-rose-600',
            ],
        ];

        $overview = [
            [
                'label' => 'User Aktif',
                'value' => $activeUsers,
            ],
            [
                'label' => 'Pendaftar Terverifikasi',
                'value' => $verifiedPendaftar,
            ],
            [
                'label' => 'Pesan Masuk',
                'value' => array_sum($messageStats),
            ],
            [
                'label' => 'Aktivitas Tercatat',
                'value' => LogAktivitas::count(),
            ],
        ];

        return view('admin.dashboard', compact(
            'stats',
            'overview',
            'ppdbStatuses',
            'contentStats',
            'messageStats',
            'recentActivities'
        ));
    }
}
