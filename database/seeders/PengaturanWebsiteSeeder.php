<?php

namespace Database\Seeders;

use App\Models\PengaturanWebsite;
use Illuminate\Database\Seeder;

class PengaturanWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah sudah ada data
        if (PengaturanWebsite::count() == 0) {
            PengaturanWebsite::create([
                'nama_website' => 'Website Sekolah',
                'logo' => null,
                'logo_small' => null,
                'favicon' => null,
                'primary_color' => '#4361ee',
            ]);
            $this->command->info('Pengaturan website berhasil ditambahkan!');
        } else {
            $this->command->info('Pengaturan website sudah ada!');
        }
    }
}
