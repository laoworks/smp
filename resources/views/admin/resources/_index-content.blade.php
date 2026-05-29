<div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Data {{ $resource['label'] }}
        </h1>

        <p class="mt-1 text-gray-500">
            Kelola seluruh data {{ strtolower($resource['label']) }}
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <form method="GET" class="hidden md:block admin-live-search-form">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari data..."
                class="w-64 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 admin-live-search-input"
            >
        </form>

        @if($resource['exportable'] ?? false)
            <a
                href="{{ route($resource['route_name'] . '.export.excel', request()->only('search')) }}"
                class="inline-flex items-center gap-2 px-4 py-3 font-semibold text-emerald-700 transition bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 rounded-xl"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-8m0 8l-3-3m3 3l3-3M4 18h16"/>
                </svg>
                Export Excel
            </a>

            <a
                href="{{ route($resource['route_name'] . '.export.pdf', request()->only('search')) }}"
                class="inline-flex items-center gap-2 px-4 py-3 font-semibold text-rose-700 transition bg-rose-50 border border-rose-200 hover:bg-rose-100 rounded-xl"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-8m0 8l-3-3m3 3l3-3M4 18h16"/>
                </svg>
                Export PDF
            </a>
        @endif

        <a
            href="{{ route($resource['route_name'] . '.create') }}"
            class="inline-flex items-center gap-2 px-5 py-3 font-semibold text-white transition bg-indigo-600 shadow hover:bg-indigo-700 rounded-xl"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah {{ $resource['label'] }}
        </a>
    </div>
</div>

<div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b bg-gray-50">
        <div>
            <h2 class="font-semibold text-gray-800">
                Daftar {{ $resource['label'] }}
            </h2>

            <p class="text-sm text-gray-500">
                Total {{ $records->total() }} data
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">No</th>
                    <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">{{ $resource['label'] }}</th>
                    @foreach($resource['table_fields'] as $field)
                        <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">{{ $field['label'] }}</th>
                    @endforeach
                    <th class="px-6 py-4 text-xs font-bold text-right text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($records as $index => $record)
                    @php
                        $preview = $manager->getPreviewImageUrl($record, $resource);
                        $title = $manager->getTitle($record, $resource);
                    @endphp

                    <tr class="transition hover:bg-gray-50">
                        <td class="px-6 py-5 text-sm text-gray-500">
                            {{ $records->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="shrink-0">
                                    @if($preview)
                                        <img
                                            src="{{ $preview }}"
                                            alt="{{ $title }}"
                                            class="object-cover border border-gray-200 shadow-sm w-14 h-14 rounded-xl"
                                        >
                                    @else
                                        <div class="flex items-center justify-center text-lg font-bold text-indigo-700 bg-indigo-100 w-14 h-14 rounded-xl">
                                            {{ strtoupper(substr($title, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $title }}</h3>
                                    <p class="text-sm text-gray-500">Manajemen {{ strtolower($resource['label']) }}</p>
                                    <a
                                        href="{{ route($resource['route_name'] . '.show', $record->getKey()) }}"
                                        class="inline-block mt-1 text-xs text-indigo-600 hover:text-indigo-800 hover:underline"
                                    >
                                        Lihat detail
                                    </a>
                                </div>
                            </div>
                        </td>

                        @foreach($resource['table_fields'] as $field)
                            <td class="px-6 py-5 text-sm text-gray-700">
                                @if($field['type'] === 'boolean')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full {{ $record->{$field['name']} ? 'text-green-700 bg-green-100' : 'text-gray-700 bg-gray-100' }}">
                                        <span class="w-2 h-2 rounded-full {{ $record->{$field['name']} ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                        {{ $record->{$field['name']} ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                @else
                                    {{ $manager->displayValue($record, $field) }}
                                @endif
                            </td>
                        @endforeach

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    href="{{ route($resource['route_name'] . '.show', $record->getKey()) }}"
                                    class="px-4 py-2 text-sm font-medium text-white transition bg-blue-500 rounded-lg hover:bg-blue-600"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route($resource['route_name'] . '.edit', $record->getKey()) }}"
                                    class="px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-amber-500 hover:bg-amber-600"
                                >
                                    Edit
                                </a>

                                <button
                                    type="button"
                                    onclick="confirmDelete('{{ $record->getKey() }}', '{{ addslashes($title) }}')"
                                    class="px-4 py-2 text-sm font-medium text-white transition bg-red-600 rounded-lg hover:bg-red-700"
                                >
                                    Delete
                                </button>

                                <form
                                    id="delete-form-{{ $record->getKey() }}"
                                    action="{{ route($resource['route_name'] . '.destroy', $record->getKey()) }}"
                                    method="POST"
                                    class="hidden"
                                >
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + count($resource['table_fields']) }}" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-20 h-20 mb-4 bg-gray-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-700">
                                    Belum ada data
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Tambahkan data {{ strtolower($resource['label']) }} pertama sekarang.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($records->hasPages())
    <div class="mt-6">
        {{ $records->links() }}
    </div>
@endif
