<!DOCTYPE html>
<html lang="id">
@php
    $authUser = auth()->user();
    $userName = $authUser?->name ?? 'Admin';
    $userEmail = $authUser?->email ?? '-';
    $userRole = $authUser && method_exists($authUser, 'getRoleNames')
        ? ($authUser->getRoleNames()->first() ?? 'Admin')
        : 'Admin';
    $userPhoto = $authUser && filled($authUser->foto) ? asset('storage/' . $authUser->foto) : null;
    $userInitials = collect(explode(' ', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
    $unreadMessageCount = \App\Models\PesanKontak::query()
        ->where('is_read', false)
        ->count();
    $pendingRegistrationCount = \App\Models\Pendaftar::query()
        ->where('status_verifikasi', 'pending')
        ->count();
    $adminNotificationCount = $unreadMessageCount + $pendingRegistrationCount;
    $recentContactNotifications = \App\Models\PesanKontak::query()
        ->latest('id')
        ->take(5)
        ->get();
    $recentRegistrationNotifications = \App\Models\Pendaftar::query()
        ->latest('tanggal_daftar')
        ->latest('id')
        ->take(5)
        ->get();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @include('components.favicon')
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .js-rich-text-wrapper {
            position: relative;
            z-index: 5;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .js-rich-text-toolbar {
            position: relative;
            z-index: 20;
            overflow: visible;
            background: #fff;
            border: 1px solid #d1d5db;
            border-bottom: 0;
            border-radius: 0.75rem 0.75rem 0 0;
        }

        .js-rich-text-toolbar.ql-toolbar.ql-snow {
            border: 0;
            background: #f9fafb;
        }

        .js-rich-text-editor.ql-container.ql-snow {
            position: relative;
            z-index: 1;
            border: 1px solid #d1d5db;
            border-radius: 0 0 0.75rem 0.75rem;
            background: #fff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            height: 260px;
            min-height: 260px;
            max-height: 260px;
            margin-bottom: 0;
        }

        .js-rich-text-editor .ql-editor {
            min-height: 260px;
            max-height: 260px;
            overflow-y: auto;
            font-size: 0.875rem;
            line-height: 1.75;
            padding-bottom: 2rem;
        }

        .js-rich-text-editor .ql-editor.ql-blank::before {
            left: 15px;
            right: 15px;
            font-style: normal;
            color: #9ca3af;
        }

        .js-rich-text-editor .ql-tooltip {
            z-index: 40;
        }

        .js-rich-text-toolbar .ql-picker {
            position: relative;
        }

        .js-rich-text-toolbar .ql-picker.ql-expanded .ql-picker-label {
            color: #4f46e5;
            border-color: #c7d2fe;
        }

        .js-rich-text-toolbar .ql-picker-options {
            z-index: 50;
            max-height: 220px;
            overflow-y: auto;
            border-radius: 0.75rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        }
    </style>
</head>

<body class="h-screen overflow-hidden bg-gray-100">
<div
    id="admin-sidebar-backdrop"
    class="fixed inset-0 z-30 hidden bg-gray-900/50 backdrop-blur-[1px] lg:hidden"
></div>
<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    @include('components.admin-sidebar')

    {{-- MAIN CONTENT --}}
    <div class="flex flex-col flex-1 min-w-0 h-screen overflow-hidden lg:pl-72">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-20 px-4 py-4 bg-white border-b border-gray-200 shadow-sm sm:px-6 shrink-0">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        id="admin-sidebar-toggle"
                        class="inline-flex items-center justify-center w-11 h-11 text-gray-600 transition bg-gray-100 rounded-xl lg:hidden hover:bg-gray-200 hover:text-gray-900"
                        aria-label="Buka sidebar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-lg font-bold text-gray-900 sm:text-xl">Admin Panel</h1>
                        <p class="hidden text-sm text-gray-500 sm:block">Kelola data website dan konten sekolah</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative" id="admin-notification-dropdown-wrapper">
                        <button
                            type="button"
                            id="admin-notification-dropdown-toggle"
                            class="relative inline-flex items-center justify-center w-11 h-11 text-gray-600 transition border border-gray-200 bg-white/95 rounded-2xl hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600"
                            aria-expanded="false"
                            aria-label="Buka notifikasi"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                            </svg>
                            @if($adminNotificationCount > 0)
                                <span class="absolute -top-1 -right-1 inline-flex min-w-[1.35rem] items-center justify-center rounded-full bg-red-500 px-1 py-0.5 text-[11px] font-bold text-white">
                                    {{ $adminNotificationCount > 99 ? '99+' : $adminNotificationCount }}
                                </span>
                            @endif
                        </button>

                        <div
                            id="admin-notification-dropdown"
                            class="absolute right-0 z-30 hidden w-[23rem] max-w-[calc(100vw-2rem)] p-2 mt-3 bg-white border border-gray-200 shadow-xl top-full rounded-2xl"
                        >
                            <div class="flex items-start justify-between gap-3 px-3 py-3 rounded-xl bg-gray-50">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Notifikasi Admin</p>
                                    <p class="mt-1 text-xs text-gray-500">Pantau pesan baru dan pendaftaran masuk</p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                    {{ $adminNotificationCount }} item
                                </span>
                            </div>

                            <div class="pt-2 mt-2 space-y-3 border-t border-gray-100">
                                <div>
                                    <div class="flex items-center justify-between px-3 mb-2">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Pesan Kontak</p>
                                        <a href="{{ route('admin.pesan-kontak.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                            Buka
                                        </a>
                                    </div>

                                    <div class="space-y-1">
                                        @forelse($recentContactNotifications as $message)
                                            <a
                                                href="{{ route('admin.pesan-kontak.index', ['search' => $message->nama]) }}"
                                                class="flex items-start gap-3 px-3 py-2 transition rounded-xl hover:bg-gray-50"
                                            >
                                                <span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full {{ $message->is_read ? 'bg-gray-300' : 'bg-emerald-500' }}"></span>
                                                <span class="min-w-0 flex-1">
                                                    <span class="block text-sm font-medium text-gray-900 truncate">{{ $message->nama ?: 'Pengirim tidak dikenal' }}</span>
                                                    <span class="block mt-0.5 text-xs text-gray-500 truncate">{{ $message->subjek ?: 'Tanpa subjek' }}</span>
                                                    <span class="block mt-1 text-xs text-gray-400">{{ $message->created_at?->diffForHumans() ?: '-' }}</span>
                                                </span>
                                            </a>
                                        @empty
                                            <div class="px-3 py-2 text-sm text-gray-500">Belum ada pesan terbaru.</div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between px-3 mb-2">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Pendaftaran</p>
                                        <a href="{{ route('admin.pendaftar.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                            Buka
                                        </a>
                                    </div>

                                    <div class="space-y-1">
                                        @forelse($recentRegistrationNotifications as $pendaftar)
                                            <a
                                                href="{{ route('admin.pendaftar.index', ['search' => $pendaftar->nama_lengkap]) }}"
                                                class="flex items-start gap-3 px-3 py-2 transition rounded-xl hover:bg-gray-50"
                                            >
                                                <span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full {{ $pendaftar->status_verifikasi === 'pending' ? 'bg-amber-500' : 'bg-gray-300' }}"></span>
                                                <span class="min-w-0 flex-1">
                                                    <span class="block text-sm font-medium text-gray-900 truncate">{{ $pendaftar->nama_lengkap ?: 'Pendaftar baru' }}</span>
                                                    <span class="block mt-0.5 text-xs text-gray-500 truncate">
                                                        {{ $pendaftar->no_pendaftaran ?: 'Nomor belum tersedia' }} • {{ ucfirst($pendaftar->status_verifikasi ?: 'pending') }}
                                                    </span>
                                                    <span class="block mt-1 text-xs text-gray-400">{{ $pendaftar->tanggal_daftar?->diffForHumans() ?: ($pendaftar->created_at?->diffForHumans() ?: '-') }}</span>
                                                </span>
                                            </a>
                                        @empty
                                            <div class="px-3 py-2 text-sm text-gray-500">Belum ada pendaftaran terbaru.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative" id="admin-profile-dropdown-wrapper">
                        <button
                            type="button"
                            id="admin-profile-dropdown-toggle"
                            class="flex items-center gap-3 px-3 py-2 text-left transition border border-gray-200 bg-white/95 rounded-2xl hover:border-indigo-200 hover:bg-indigo-50"
                            aria-expanded="false"
                        >
                            @if($userPhoto)
                                <img
                                    src="{{ $userPhoto }}"
                                    alt="{{ $userName }}"
                                    class="object-cover w-11 h-11 border border-gray-200 rounded-full"
                                >
                            @else
                                <div class="flex items-center justify-center w-11 h-11 text-sm font-bold text-white rounded-full bg-gradient-to-br from-indigo-500 to-violet-500">
                                    {{ $userInitials ?: 'AD' }}
                                </div>
                            @endif

                            <div class="hidden sm:block">
                                <p class="text-sm font-semibold text-gray-900">{{ $userName }}</p>
                                <p class="text-xs text-gray-500">{{ str_replace('_', ' ', ucfirst($userRole)) }}</p>
                            </div>

                            <svg class="hidden w-4 h-4 text-gray-500 sm:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            id="admin-profile-dropdown"
                            class="absolute right-0 z-30 hidden w-72 p-2 mt-3 bg-white border border-gray-200 shadow-xl top-full rounded-2xl"
                        >
                            <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-gray-50">
                                @if($userPhoto)
                                    <img
                                        src="{{ $userPhoto }}"
                                        alt="{{ $userName }}"
                                        class="object-cover w-12 h-12 border border-gray-200 rounded-full"
                                    >
                                @else
                                    <div class="flex items-center justify-center w-12 h-12 text-sm font-bold text-white rounded-full bg-gradient-to-br from-indigo-500 to-violet-500">
                                        {{ $userInitials ?: 'AD' }}
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $userName }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $userEmail }}</p>
                                    <p class="mt-1 text-xs font-medium text-indigo-600 uppercase tracking-[0.18em]">{{ str_replace('_', ' ', $userRole) }}</p>
                                </div>
                            </div>

                            <div class="pt-2 mt-2 space-y-1 border-t border-gray-100">
                                <a
                                    href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 transition rounded-xl hover:bg-gray-100 hover:text-gray-900"
                                >
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Profil Akun</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="flex items-center w-full gap-3 px-3 py-2 text-sm font-medium text-red-600 transition rounded-xl hover:bg-red-50"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H9"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 20H6a2 2 0 01-2-2V6a2 2 0 012-2h7"/>
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 p-4 overflow-y-auto sm:p-6">
            @yield('content')
        </main>

    </div>
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        confirmButtonColor: '#4f46e5'
    });
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('admin-sidebar');
    const backdrop = document.getElementById('admin-sidebar-backdrop');
    const openButton = document.getElementById('admin-sidebar-toggle');
    const closeButton = document.getElementById('admin-sidebar-close');
    const sidebarLinks = document.querySelectorAll('.admin-sidebar-link');
    const notificationWrapper = document.getElementById('admin-notification-dropdown-wrapper');
    const notificationToggle = document.getElementById('admin-notification-dropdown-toggle');
    const notificationDropdown = document.getElementById('admin-notification-dropdown');
    const dropdownWrapper = document.getElementById('admin-profile-dropdown-wrapper');
    const dropdownToggle = document.getElementById('admin-profile-dropdown-toggle');
    const dropdown = document.getElementById('admin-profile-dropdown');

    function openSidebar() {
        if (!sidebar || !backdrop) {
            return;
        }

        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSidebar() {
        if (!sidebar || !backdrop) {
            return;
        }

        if (window.innerWidth >= 1024) {
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            return;
        }

        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function toggleDropdown(dropdownElement, toggleElement, forceOpen) {
        if (!dropdownElement || !toggleElement) {
            return;
        }

        const willOpen = typeof forceOpen === 'boolean'
            ? forceOpen
            : dropdownElement.classList.contains('hidden');

        dropdownElement.classList.toggle('hidden', !willOpen);
        toggleElement.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    }

    openButton?.addEventListener('click', openSidebar);
    closeButton?.addEventListener('click', closeSidebar);
    backdrop?.addEventListener('click', closeSidebar);

    sidebarLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });

    notificationToggle?.addEventListener('click', function () {
        toggleDropdown(dropdown, dropdownToggle, false);
        toggleDropdown(notificationDropdown, notificationToggle);
    });

    dropdownToggle?.addEventListener('click', function () {
        toggleDropdown(notificationDropdown, notificationToggle, false);
        toggleDropdown(dropdown, dropdownToggle);
    });

    document.addEventListener('click', function (event) {
        if (!notificationWrapper?.contains(event.target)) {
            toggleDropdown(notificationDropdown, notificationToggle, false);
        }

        if (!dropdownWrapper?.contains(event.target)) {
            toggleDropdown(dropdown, dropdownToggle, false);
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            sidebar?.classList.remove('-translate-x-full');
            backdrop?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        } else if (sidebar && !backdrop?.classList.contains('hidden')) {
            document.body.classList.add('overflow-hidden');
        } else {
            sidebar?.classList.add('-translate-x-full');
            document.body.classList.remove('overflow-hidden');
        }
    });
});
</script>

</body>
</html>
