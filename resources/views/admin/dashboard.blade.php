@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Dashboard Admin
            </h1>

            <p class="mt-1 text-gray-500">
                Ringkasan statistik dan analitik utama sistem sekolah
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach($overview as $item)
                <div class="px-4 py-3 bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">
                        {{ $item['label'] }}
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ number_format($item['value']) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach($stats as $item)
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            {{ $item['label'] }}
                        </p>

                        <p class="mt-3 text-4xl font-bold text-gray-900">
                            {{ number_format($item['value']) }}
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ $item['description'] }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center w-12 h-12 rounded-2xl {{ $item['icon_bg'] }} {{ $item['icon_text'] }}">
                        @if($item['label'] === 'Total User')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 21C3.732 17.5 7.523 15.75 12 15.75s8.268 1.75 9.542 5.25"/>
                            </svg>
                        @elseif($item['label'] === 'Total Pendaftar')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 16h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z"/>
                            </svg>
                        @elseif($item['label'] === 'Data Guru')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l9 4.5-9 4.5L3 7.5 12 3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10.5v3.5a5 5 0 0010 0v-3.5"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 5H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 9h10"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 13h6"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Analitik PPDB
                    </h2>

                    <p class="text-sm text-gray-500">
                        Distribusi status verifikasi pendaftar
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                @php
                    $ppdbTotal = max(array_sum($ppdbStatuses), 1);
                @endphp

                @foreach($ppdbStatuses as $label => $value)
                    <div>
                        <div class="flex items-center justify-between mb-2 text-sm">
                            <span class="font-medium text-gray-700">{{ $label }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($value) }}</span>
                        </div>

                        <div class="w-full h-2 bg-gray-100 rounded-full">
                            <div
                                class="h-2 rounded-full bg-indigo-500"
                                style="width: {{ min(($value / $ppdbTotal) * 100, 100) }}%;"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Konten Website
                </h2>

                <p class="text-sm text-gray-500">
                    Ringkasan data publikasi dan media
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach($contentStats as $label => $value)
                    <div class="p-4 border border-gray-200 rounded-2xl bg-gray-50">
                        <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">
                            {{ $label }}
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ number_format($value) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Pesan Kontak
                </h2>

                <p class="text-sm text-gray-500">
                    Status pesan yang masuk dari pengunjung
                </p>
            </div>

            <div class="space-y-4">
                @foreach($messageStats as $label => $value)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl bg-gray-50">
                        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                        <span class="text-xl font-bold text-gray-900">{{ number_format($value) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Aktivitas Terbaru
                </h2>

                <p class="text-sm text-gray-500">
                    Riwayat aktivitas terakhir pada sistem admin
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">Aksi</th>
                        <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">Tabel Terkait</th>
                        <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">Waktu</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($recentActivities as $activity)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $activity->user?->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $activity->aksi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $activity->tabel_terkait ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $activity->created_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-20 h-20 mb-4 bg-gray-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                                        </svg>
                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-700">
                                        Belum ada aktivitas
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Aktivitas admin akan tampil di dashboard setelah sistem mulai digunakan.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
