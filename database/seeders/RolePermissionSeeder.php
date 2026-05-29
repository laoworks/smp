<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ================= PERMISSIONS =================
        $permissions = [
            'view_user',
            'create_user',
            'edit_user',
            'delete_user',
            'view_profil',
            'edit_profil',
            'view_guru',
            'create_guru',
            'edit_guru',
            'delete_guru',
            'view_jurusan',
            'create_jurusan',
            'edit_jurusan',
            'delete_jurusan',
            'view_berita',
            'create_berita',
            'edit_berita',
            'delete_berita',
            'view_fasilitas',
            'create_fasilitas',
            'edit_fasilitas',
            'delete_fasilitas',
            'view_ekskul',
            'create_ekskul',
            'edit_ekskul',
            'delete_ekskul',
            'view_slider',
            'create_slider',
            'edit_slider',
            'delete_slider',
            'view_prestasi',
            'create_prestasi',
            'edit_prestasi',
            'delete_prestasi',
            'view_album',
            'create_album',
            'edit_album',
            'delete_album',
            'view_foto',
            'create_foto',
            'edit_foto',
            'delete_foto',
            'view_video',
            'create_video',
            'edit_video',
            'delete_video',
            'view_kalender',
            'create_kalender',
            'edit_kalender',
            'delete_kalender',
            'view_struktur',
            'create_struktur',
            'edit_struktur',
            'delete_struktur',
            'view_alumni',
            'create_alumni',
            'edit_alumni',
            'delete_alumni',
            'view_gelombang',
            'create_gelombang',
            'edit_gelombang',
            'delete_gelombang',
            'view_pendaftar',
            'verifikasi_pendaftar',
            'delete_pendaftar',
            'view_pesan',
            'delete_pesan',
            'view_pengaturan',
            'edit_pengaturan',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // ================= ROLES =================

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'view_berita',
            'create_berita',
            'edit_berita',
            'delete_berita',
            'view_fasilitas',
            'create_fasilitas',
            'edit_fasilitas',
            'delete_fasilitas',
            'view_prestasi',
            'create_prestasi',
            'edit_prestasi',
            'delete_prestasi',
            'view_album',
            'create_album',
            'edit_album',
            'delete_album',
            'view_slider',
            'create_slider',
            'edit_slider',
            'delete_slider',
        ]);

        $verifikator = Role::firstOrCreate(['name' => 'verifikator']);
        $verifikator->syncPermissions(['view_pendaftar', 'verifikasi_pendaftar']);

        $guru = Role::firstOrCreate(['name' => 'guru']);
        $guru->syncPermissions(['view_berita', 'view_prestasi']);

        // ================= USER ADMIN =================

        $user = User::where('email', 'admin@sekolah.sch.id')->first();

        if ($user) {
            $user->assignRole('super_admin');
        }
    }
}
