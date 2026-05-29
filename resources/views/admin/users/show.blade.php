@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail User</h1>
            <p class="text-sm text-gray-500">Informasi lengkap user</p>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            Kembali
        </a>
    </div>

    <!-- CARD -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-6">

            <!-- FOTO -->
            <div>
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}"
                         class="w-24 h-24 rounded-full object-cover border">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                        No Photo
                    </div>
                @endif
            </div>

            <!-- INFO -->
            <div class="space-y-2">

                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $user->name }}
                </h2>

                <p class="text-gray-600">
                    {{ $user->email }}
                </p>

                <div class="flex gap-2">

                    <span class="px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700">
                        {{ $user->getRoleNames()->first() ?? '-' }}
                    </span>

                    <span class="px-3 py-1 text-xs rounded-full
                        {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>

                </div>

            </div>

        </div>

        <hr class="my-6">

        <!-- DETAIL -->
        <div class="grid grid-cols-2 gap-4 text-sm">

            <div>
                <p class="text-gray-500">Nama</p>
                <p class="font-semibold">{{ $user->name }}</p>
            </div>

            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-semibold">{{ $user->email }}</p>
            </div>

            <div>
                <p class="text-gray-500">Role</p>
                <p class="font-semibold">{{ $user->getRoleNames()->implode(', ') }}</p>
            </div>

            <div>
                <p class="text-gray-500">Status</p>
                <p class="font-semibold">
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Dibuat</p>
                <p class="font-semibold">{{ $user->created_at }}</p>
            </div>

            <div>
                <p class="text-gray-500">Update</p>
                <p class="font-semibold">{{ $user->updated_at }}</p>
            </div>

        </div>

    </div>

</div>

@endsection
