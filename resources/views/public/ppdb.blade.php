@extends('layouts.public')

@section('content')
    <section class="border-b border-slate-200 pb-8" data-reveal>
        <div class="max-w-4xl">
            <p class="section-kicker">{{ $pageTitle }}</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">{{ $pageTitle }}</h1>
            <p class="mt-4 text-[15px] leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">{{ $pageDescription }}</p>
        </div>
    </section>

    <section class="grid gap-6 py-8 sm:gap-8 sm:py-10 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="space-y-5 sm:space-y-6" data-reveal>
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                    <p class="font-semibold">Form pendaftaran belum bisa dikirim.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-8">
                <div class="mb-6 border-b border-slate-200 pb-4">
                    <p class="section-kicker">Formulir Pendaftaran</p>
                    <h2 class="section-title mt-2">Isi data calon peserta didik</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                        Lengkapi data berikut dengan benar. Field utama seperti nama lengkap, gelombang, jenis kelamin, dan nomor HP sebaiknya diisi terlebih dahulu.
                    </p>
                </div>

                <form action="{{ route('public.ppdb.store') }}" method="POST" enctype="multipart/form-data" class="space-y-7 sm:space-y-8">
                    @csrf

                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-slate-900">Data Pendaftaran</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="gelombang_id" class="mb-2 block text-sm font-semibold text-slate-700">Gelombang Pendaftaran</label>
                                <select id="gelombang_id" name="gelombang_id" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]" required>
                                    <option value="">Pilih gelombang</option>
                                    @foreach($activeWaves as $wave)
                                        <option value="{{ $wave->id }}" @selected(old('gelombang_id') == $wave->id)>
                                            {{ $wave->nama_gelombang }} | {{ $wave->periode_mulai?->format('d M Y') ?: '-' }} - {{ $wave->periode_selesai?->format('d M Y') ?: '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-slate-900">Data Pribadi</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="nama_lengkap" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                                <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]" required>
                            </div>
                            <div>
                                <label for="nik" class="mb-2 block text-sm font-semibold text-slate-700">NIK</label>
                                <input id="nik" name="nik" type="text" value="{{ old('nik') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="nisn" class="mb-2 block text-sm font-semibold text-slate-700">NISN</label>
                                <input id="nisn" name="nisn" type="text" value="{{ old('nisn') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="jenis_kelamin" class="mb-2 block text-sm font-semibold text-slate-700">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]" required>
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                                    <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="agama" class="mb-2 block text-sm font-semibold text-slate-700">Agama</label>
                                <input id="agama" name="agama" type="text" value="{{ old('agama') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="tempat_lahir" class="mb-2 block text-sm font-semibold text-slate-700">Tempat Lahir</label>
                                <input id="tempat_lahir" name="tempat_lahir" type="text" value="{{ old('tempat_lahir') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="tanggal_lahir" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                                <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="anak_ke" class="mb-2 block text-sm font-semibold text-slate-700">Anak Ke</label>
                                <input id="anak_ke" name="anak_ke" type="number" min="1" max="20" value="{{ old('anak_ke') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="jumlah_saudara" class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Saudara</label>
                                <input id="jumlah_saudara" name="jumlah_saudara" type="number" min="0" max="20" value="{{ old('jumlah_saudara') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-slate-900">Kontak dan Alamat</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="no_hp" class="mb-2 block text-sm font-semibold text-slate-700">No. HP</label>
                                <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">{{ old('alamat') }}</textarea>
                            </div>
                            <div>
                                <label for="rt_rw" class="mb-2 block text-sm font-semibold text-slate-700">RT / RW</label>
                                <input id="rt_rw" name="rt_rw" type="text" value="{{ old('rt_rw') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="kelurahan" class="mb-2 block text-sm font-semibold text-slate-700">Kelurahan / Desa</label>
                                <input id="kelurahan" name="kelurahan" type="text" value="{{ old('kelurahan') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="kecamatan" class="mb-2 block text-sm font-semibold text-slate-700">Kecamatan</label>
                                <input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="kota" class="mb-2 block text-sm font-semibold text-slate-700">Kabupaten / Kota</label>
                                <input id="kota" name="kota" type="text" value="{{ old('kota') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="provinsi" class="mb-2 block text-sm font-semibold text-slate-700">Provinsi</label>
                                <input id="provinsi" name="provinsi" type="text" value="{{ old('provinsi') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-slate-900">Sekolah Asal</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="asal_sekolah" class="mb-2 block text-sm font-semibold text-slate-700">Asal Sekolah</label>
                                <input id="asal_sekolah" name="asal_sekolah" type="text" value="{{ old('asal_sekolah') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="tahun_lulus" class="mb-2 block text-sm font-semibold text-slate-700">Tahun Lulus</label>
                                <input id="tahun_lulus" name="tahun_lulus" type="number" min="1901" max="2155" value="{{ old('tahun_lulus') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ijazah_number" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Ijazah</label>
                                <input id="ijazah_number" name="ijazah_number" type="text" value="{{ old('ijazah_number') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-slate-900">Data Orang Tua / Wali</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="ayah_nama" class="mb-2 block text-sm font-semibold text-slate-700">Nama Ayah</label>
                                <input id="ayah_nama" name="ayah_nama" type="text" value="{{ old('ayah_nama') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ayah_no_hp" class="mb-2 block text-sm font-semibold text-slate-700">No. HP Ayah</label>
                                <input id="ayah_no_hp" name="ayah_no_hp" type="text" value="{{ old('ayah_no_hp') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ayah_pekerjaan" class="mb-2 block text-sm font-semibold text-slate-700">Pekerjaan Ayah</label>
                                <input id="ayah_pekerjaan" name="ayah_pekerjaan" type="text" value="{{ old('ayah_pekerjaan') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ayah_pendidikan" class="mb-2 block text-sm font-semibold text-slate-700">Pendidikan Ayah</label>
                                <input id="ayah_pendidikan" name="ayah_pendidikan" type="text" value="{{ old('ayah_pendidikan') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ibu_nama" class="mb-2 block text-sm font-semibold text-slate-700">Nama Ibu</label>
                                <input id="ibu_nama" name="ibu_nama" type="text" value="{{ old('ibu_nama') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ibu_no_hp" class="mb-2 block text-sm font-semibold text-slate-700">No. HP Ibu</label>
                                <input id="ibu_no_hp" name="ibu_no_hp" type="text" value="{{ old('ibu_no_hp') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ibu_pekerjaan" class="mb-2 block text-sm font-semibold text-slate-700">Pekerjaan Ibu</label>
                                <input id="ibu_pekerjaan" name="ibu_pekerjaan" type="text" value="{{ old('ibu_pekerjaan') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="ibu_pendidikan" class="mb-2 block text-sm font-semibold text-slate-700">Pendidikan Ibu</label>
                                <input id="ibu_pendidikan" name="ibu_pendidikan" type="text" value="{{ old('ibu_pendidikan') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="wali_nama" class="mb-2 block text-sm font-semibold text-slate-700">Nama Wali</label>
                                <input id="wali_nama" name="wali_nama" type="text" value="{{ old('wali_nama') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div>
                                <label for="wali_hubungan" class="mb-2 block text-sm font-semibold text-slate-700">Hubungan Wali</label>
                                <input id="wali_hubungan" name="wali_hubungan" type="text" value="{{ old('wali_hubungan') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-slate-900">Upload Dokumen</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="foto_kk" class="mb-2 block text-sm font-semibold text-slate-700">Kartu Keluarga</label>
                                <input id="foto_kk" name="foto_kk" type="file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            </div>
                            <div>
                                <label for="foto_akte" class="mb-2 block text-sm font-semibold text-slate-700">Akte Kelahiran</label>
                                <input id="foto_akte" name="foto_akte" type="file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            </div>
                            <div>
                                <label for="foto_ijazah" class="mb-2 block text-sm font-semibold text-slate-700">Ijazah</label>
                                <input id="foto_ijazah" name="foto_ijazah" type="file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            </div>
                            <div>
                                <label for="foto_nilai" class="mb-2 block text-sm font-semibold text-slate-700">Nilai / Raport</label>
                                <input id="foto_nilai" name="foto_nilai" type="file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            </div>
                            <div class="md:col-span-2">
                                <label for="foto_sertifikat" class="mb-2 block text-sm font-semibold text-slate-700">Sertifikat Pendukung</label>
                                <input id="foto_sertifikat" name="foto_sertifikat" type="file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="mb-2 block text-sm font-semibold text-slate-700">Catatan Tambahan</label>
                        <textarea id="keterangan" name="keterangan" rows="4" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-[var(--brand-primary)] focus:ring-[var(--brand-primary)]" placeholder="Tambahkan informasi yang perlu diketahui sekolah...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="flex flex-col items-start gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:flex-wrap sm:items-center">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[var(--brand-primary)] px-6 py-3 text-sm font-semibold text-white transition hover:opacity-95 sm:w-auto">
                            Kirim Pendaftaran
                        </button>
                        <p class="text-sm text-slate-500">Setelah dikirim, data akan masuk ke panel admin untuk diverifikasi.</p>
                    </div>
                </form>
            </div>
        </div>

        <aside class="space-y-4 sm:space-y-5" data-reveal data-reveal-delay="120">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Informasi</p>
                <h2 class="mt-3 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">Ringkasan PPDB</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Halaman ini menampilkan form pendaftaran dan gelombang terbaru agar calon peserta didik dapat langsung mendaftar dari website sekolah.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Petunjuk</p>
                <div class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
                    <p>Pastikan nomor HP aktif agar sekolah mudah menghubungi Anda.</p>
                    <p>Upload dokumen dalam format `JPG`, `PNG`, atau `PDF` maksimal 2MB per file.</p>
                    <p>Nomor pendaftaran dibuat otomatis setelah data berhasil dikirim.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Gelombang Aktif</p>
                <div class="mt-4 grid gap-3">
                    @forelse($activeWaves as $wave)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $wave->nama_gelombang }}</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $wave->periode_mulai?->format('d M Y') ?: '-' }} - {{ $wave->periode_selesai?->format('d M Y') ?: '-' }}
                                    </p>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-emerald-700">Aktif</span>
                            </div>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Kuota</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $wave->kuota ?: '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Biaya</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">
                                        {{ filled($wave->biaya_pendaftaran) ? 'Rp ' . number_format((float) $wave->biaya_pendaftaran, 0, ',', '.') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm leading-7 text-slate-500">Belum ada gelombang aktif saat ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 soft-card sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Arsip Gelombang</p>
                <div class="mt-4 grid gap-3">
                    @forelse($otherWaves as $wave)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <p class="text-sm font-bold text-slate-900">{{ $wave->nama_gelombang }}</p>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ $wave->periode_mulai?->format('d M Y') ?: '-' }} - {{ $wave->periode_selesai?->format('d M Y') ?: '-' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm leading-7 text-slate-500">Belum ada arsip gelombang lainnya.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>
@endsection
