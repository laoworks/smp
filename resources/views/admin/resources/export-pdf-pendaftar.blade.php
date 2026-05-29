<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export {{ $resource['label'] }}</title>
    <style>
        @page {
            margin: 20px 18px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        h1 {
            margin: 0 0 4px;
            font-size: 18px;
        }

        .meta {
            margin: 0 0 18px;
            color: #4b5563;
        }

        .record {
            page-break-inside: avoid;
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid #d1d5db;
        }

        .record:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: 0;
        }

        .record-header {
            margin-bottom: 12px;
        }

        .record-title {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            color: #111827;
        }

        .record-subtitle {
            margin: 4px 0 0;
            color: #4b5563;
            font-size: 10px;
        }

        .section {
            margin-top: 10px;
        }

        .section-title {
            margin: 0 0 6px;
            font-size: 11px;
            font-weight: bold;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }

        .label {
            width: 24%;
            background: #f9fafb;
            font-weight: bold;
            color: #374151;
        }

        .value {
            width: 26%;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4338ca;
            font-size: 9px;
            font-weight: bold;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <h1>Data {{ $resource['label'] }}</h1>
    <p class="meta">Diekspor pada {{ $exportedAt->format('d-m-Y H:i') }}</p>

    @forelse($records as $index => $record)
        @php
            $documentStatuses = [
                'Kartu Keluarga' => filled($record->foto_kk) ? 'Tersedia' : 'Belum ada',
                'Akte Kelahiran' => filled($record->foto_akte) ? 'Tersedia' : 'Belum ada',
                'Ijazah' => filled($record->foto_ijazah) ? 'Tersedia' : 'Belum ada',
                'Nilai' => filled($record->foto_nilai) ? 'Tersedia' : 'Belum ada',
                'Sertifikat' => filled($record->foto_sertifikat) ? 'Tersedia' : 'Belum ada',
            ];
        @endphp

        <div class="record">
            <div class="record-header">
                <p class="record-title">{{ $index + 1 }}. {{ $record->nama_lengkap ?: 'Nama pendaftar belum diisi' }}</p>
                <p class="record-subtitle">
                    No. Pendaftaran: {{ $record->no_pendaftaran ?: '-' }}
                    |
                    Tanggal Daftar: {{ optional($record->tanggal_daftar)->format('d-m-Y H:i') ?: '-' }}
                </p>
            </div>

            <div class="section">
                <p class="section-title">Identitas Siswa</p>
                <table>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="value">{{ $record->nama_lengkap ?: '-' }}</td>
                        <td class="label">Jenis Kelamin</td>
                        <td class="value">{{ $record->jenis_kelamin ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="value">{{ $record->nik ?: '-' }}</td>
                        <td class="label">NISN</td>
                        <td class="value">{{ $record->nisn ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="value">{{ collect([$record->tempat_lahir, optional($record->tanggal_lahir)->format('d-m-Y')])->filter()->implode(', ') ?: '-' }}</td>
                        <td class="label">Agama</td>
                        <td class="value">{{ $record->agama ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Anak Ke</td>
                        <td class="value">{{ $record->anak_ke ?: '-' }}</td>
                        <td class="label">Jumlah Saudara</td>
                        <td class="value">{{ $record->jumlah_saudara ?: '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p class="section-title">Kontak dan Domisili</p>
                <table>
                    <tr>
                        <td class="label">Email</td>
                        <td class="value">{{ $record->email ?: '-' }}</td>
                        <td class="label">No. HP</td>
                        <td class="value">{{ $record->no_hp ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="value">{{ $record->alamat ?: '-' }}</td>
                        <td class="label">RT / RW</td>
                        <td class="value">{{ $record->rt_rw ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kelurahan</td>
                        <td class="value">{{ $record->kelurahan ?: '-' }}</td>
                        <td class="label">Kecamatan</td>
                        <td class="value">{{ $record->kecamatan ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kota / Kabupaten</td>
                        <td class="value">{{ $record->kota ?: '-' }}</td>
                        <td class="label">Provinsi</td>
                        <td class="value">{{ $record->provinsi ?: '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p class="section-title">Data Sekolah Asal</p>
                <table>
                    <tr>
                        <td class="label">Asal Sekolah</td>
                        <td class="value">{{ $record->asal_sekolah ?: '-' }}</td>
                        <td class="label">Tahun Lulus</td>
                        <td class="value">{{ $record->tahun_lulus ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nomor Ijazah</td>
                        <td class="value">{{ $record->ijazah_number ?: '-' }}</td>
                        <td class="label">Gelombang PPDB</td>
                        <td class="value">{{ optional($record->gelombang)->nama_gelombang ?: '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p class="section-title">Data Orang Tua dan Wali</p>
                <table>
                    <tr>
                        <td class="label">Ayah</td>
                        <td class="value">
                            {{ $record->ayah_nama ?: '-' }}<br>
                            <span class="muted">{{ $record->ayah_pekerjaan ?: '-' }} | {{ $record->ayah_pendidikan ?: '-' }} | {{ $record->ayah_no_hp ?: '-' }}</span>
                        </td>
                        <td class="label">Ibu</td>
                        <td class="value">
                            {{ $record->ibu_nama ?: '-' }}<br>
                            <span class="muted">{{ $record->ibu_pekerjaan ?: '-' }} | {{ $record->ibu_pendidikan ?: '-' }} | {{ $record->ibu_no_hp ?: '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Wali</td>
                        <td class="value">{{ $record->wali_nama ?: '-' }}</td>
                        <td class="label">Pekerjaan / Hubungan</td>
                        <td class="value">{{ collect([$record->wali_pekerjaan, $record->wali_hubungan])->filter()->implode(' | ') ?: '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p class="section-title">Dokumen Pendukung</p>
                <table>
                    @foreach(array_chunk($documentStatuses, 2, true) as $chunk)
                        <tr>
                            @foreach($chunk as $label => $status)
                                <td class="label">{{ $label }}</td>
                                <td class="value">{{ $status }}</td>
                            @endforeach
                            @if(count($chunk) === 1)
                                <td class="label">-</td>
                                <td class="value">-</td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="section">
                <p class="section-title">Verifikasi</p>
                <table>
                    <tr>
                        <td class="label">Status Verifikasi</td>
                        <td class="value"><span class="badge">{{ $record->status_verifikasi ?: 'Belum diverifikasi' }}</span></td>
                        <td class="label">Tanggal Verifikasi</td>
                        <td class="value">{{ optional($record->tanggal_verifikasi)->format('d-m-Y H:i') ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Verifikator</td>
                        <td class="value">{{ optional($record->verifikator)->name ?: '-' }}</td>
                        <td class="label">Keterangan</td>
                        <td class="value">{{ $record->keterangan ?: '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @empty
        <p>Tidak ada data untuk diekspor.</p>
    @endforelse
</body>
</html>
