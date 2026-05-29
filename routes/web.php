<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/pencarian', [PublicPageController::class, 'search'])->name('public.search');
Route::get('/kontak', [PublicPageController::class, 'contact'])->name('public.contact');
Route::post('/kontak', [PublicPageController::class, 'storeContact'])->name('public.contact.store');
Route::get('/profil-sekolah', [PublicPageController::class, 'profile'])->name('public.profile');
Route::get('/struktur-organisasi', [PublicPageController::class, 'structure'])->name('public.structure');
Route::get('/fasilitas', [PublicPageController::class, 'facilities'])->name('public.facilities.index');
Route::get('/fasilitas/{fasilitas}', [PublicPageController::class, 'facility'])->name('public.facilities.show');
Route::get('/ekstrakurikuler', [PublicPageController::class, 'extracurriculars'])->name('public.extracurriculars.index');
Route::get('/ekstrakurikuler/{ekstrakurikuler}', [PublicPageController::class, 'extracurricular'])->name('public.extracurriculars.show');
Route::get('/prestasi', [PublicPageController::class, 'achievements'])->name('public.achievements.index');
Route::get('/prestasi/{prestasi}', [PublicPageController::class, 'achievement'])->name('public.achievements.show');
Route::get('/guru', [PublicPageController::class, 'teachers'])->name('public.teachers.index');
Route::get('/guru/{guru}', [PublicPageController::class, 'teacher'])->name('public.teachers.show');
Route::get('/alumni', [PublicPageController::class, 'alumni'])->name('public.alumni.index');
Route::get('/alumni/{alumni}', [PublicPageController::class, 'alumnus'])->name('public.alumni.show');
Route::get('/kalender-akademik', [PublicPageController::class, 'calendars'])->name('public.calendar.index');
Route::get('/kalender-akademik/{kalenderAkademik}', [PublicPageController::class, 'calendar'])->name('public.calendar.show');
Route::get('/berita', [PublicPageController::class, 'news'])->name('public.news.index');
Route::get('/berita/{beritaPengumuman}', [PublicPageController::class, 'newsShow'])->name('public.news.show');
Route::get('/galeri', [PublicPageController::class, 'gallery'])->name('public.gallery.index');
Route::get('/galeri/{album}', [PublicPageController::class, 'galleryShow'])->name('public.gallery.show');
Route::get('/ppdb', [PublicPageController::class, 'ppdb'])->name('public.ppdb.index');
Route::post('/ppdb', [PublicPageController::class, 'storePpdb'])->name('public.ppdb.store');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin|super_admin'])
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('users', UserController::class);

        foreach (array_keys(config('admin_resources', [])) as $resource) {
            Route::controller(ResourceController::class)
                ->prefix($resource)
                ->name($resource . '.')
                ->group(function () use ($resource) {
                    Route::get('/', 'index')->name('index')->defaults('resource', $resource);
                    Route::get('/create', 'create')->name('create')->defaults('resource', $resource);
                    Route::post('/', 'store')->name('store')->defaults('resource', $resource);
                    Route::get('/export/excel', 'exportExcel')->name('export.excel')->defaults('resource', $resource);
                    Route::get('/export/pdf', 'exportPdf')->name('export.pdf')->defaults('resource', $resource);
                    Route::get('/{record}', 'show')->name('show')->defaults('resource', $resource);
                    Route::get('/{record}/edit', 'edit')->name('edit')->defaults('resource', $resource);
                    Route::put('/{record}', 'update')->name('update')->defaults('resource', $resource);
                    Route::delete('/{record}', 'destroy')->name('destroy')->defaults('resource', $resource);
                });
        }
    });

require __DIR__ . '/auth.php';
