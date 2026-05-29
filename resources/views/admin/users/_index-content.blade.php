<div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Data Users
        </h1>

        <p class="mt-1 text-gray-500">
            Kelola seluruh user sistem sekolah
        </p>
    </div>

    <div class="flex items-center gap-3">
        <form method="GET" class="hidden md:block admin-live-search-form">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari user..."
                class="w-64 border-gray-300 shadow-sm rounded-xl focus:border-indigo-500 focus:ring-indigo-500 admin-live-search-input"
            >
        </form>

        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 font-semibold text-white transition bg-indigo-600 shadow hover:bg-indigo-700 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User
        </a>
    </div>
</div>

@if(session('success'))
    <div class="px-5 py-4 mb-5 text-green-700 border border-green-200 rounded-xl bg-green-50">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b bg-gray-50">
        <div>
            <h2 class="font-semibold text-gray-800">
                Daftar User
            </h2>

            <p class="text-sm text-gray-500">
                Total {{ $users->total() }} user
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">No</th>
                    <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">User</th>
                    <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-4 text-xs font-bold text-left text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-right text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-6 py-5 text-sm text-gray-500">
                            {{ $users->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="shrink-0">
                                    @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}"
                                             alt="{{ $user->name }}"
                                             class="object-cover border border-gray-200 shadow-sm w-14 h-14 rounded-xl">
                                    @else
                                        <div class="flex items-center justify-center text-lg font-bold text-indigo-700 bg-indigo-100 w-14 h-14 rounded-xl">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $user->name }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        User Sistem Sekolah
                                    </p>

                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="inline-block mt-1 text-xs text-indigo-600 hover:text-indigo-800 hover:underline">
                                        Lihat detail
                                    </a>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-sm text-gray-600">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                                {{ $user->getRoleNames()->first() ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">
                                    <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="px-4 py-2 text-sm font-medium text-white transition bg-blue-500 rounded-lg hover:bg-blue-600">
                                    View
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-amber-500 hover:bg-amber-600">
                                    Edit
                                </a>

                                <button type="button"
                                        onclick="confirmDelete({{ $user->id }})"
                                        class="px-4 py-2 text-sm font-medium text-white transition bg-red-600 rounded-lg hover:bg-red-700">
                                    Delete
                                </button>

                                <form id="delete-form-{{ $user->id }}"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST"
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-20 h-20 mb-4 bg-gray-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-10 h-10 text-gray-400"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-700">
                                    Tidak ada data user
                                </h3>

                                <p class="mt-1 text-gray-500">
                                    Tambahkan user baru untuk mulai menggunakan sistem.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $users->links() }}
</div>
