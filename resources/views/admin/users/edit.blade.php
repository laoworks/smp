@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Edit User</h1>

    <div class="bg-white p-6 rounded-xl shadow-sm">

        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- NAME -->
            <div class="mb-4">
                <label>Nama</label>
                <input type="text" name="name"
                       value="{{ $user->name }}"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- EMAIL -->
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email"
                       value="{{ $user->email }}"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label>Password (opsional)</label>
                <input type="password" name="password"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- FOTO -->
            <div class="mb-4">
                <label>Foto</label>

                @if($user->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$user->foto) }}"
                             class="w-16 h-16 rounded-full object-cover">
                    </div>
                @endif

                <input type="file" name="foto"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- STATUS -->
            <div class="mb-4">
                <label>Status</label>
                <select name="is_active"
                        class="w-full border p-3 rounded-lg mt-1">
                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- ROLE -->
            <div class="mb-6">
                <label>Role</label>
                <select name="role"
                        class="w-full border p-3 rounded-lg mt-1">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ $user->getRoleNames()->first() == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="w-full bg-green-600 text-white py-3 rounded-lg">
                Update User
            </button>

        </form>

    </div>
</div>

@endsection
