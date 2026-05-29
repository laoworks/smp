@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-3xl font-bold">Tambah User</h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm">

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- NAME -->
            <div class="mb-4">
                <label class="text-sm font-medium">Nama</label>
                <input type="text" name="name"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label class="text-sm font-medium">Password</label>
                <input type="password" name="password"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- FOTO -->
            <div class="mb-4">
                <label class="text-sm font-medium">Foto</label>
                <input type="file" name="foto"
                       class="w-full border p-3 rounded-lg mt-1">
            </div>

            <!-- STATUS -->
            <div class="mb-4">
                <label class="text-sm font-medium">Status</label>
                <select name="is_active"
                        class="w-full border p-3 rounded-lg mt-1">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <!-- ROLE -->
            <div class="mb-6">
                <label class="text-sm font-medium">Role</label>
                <select name="role"
                        class="w-full border p-3 rounded-lg mt-1">
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                    <option value="verifikator">Verifikator</option>
                </select>
            </div>

            <!-- BUTTON -->
            <button class="w-full bg-indigo-600 text-white py-3 rounded-lg">
                Simpan User
            </button>

        </form>

    </div>
</div>

@endsection
