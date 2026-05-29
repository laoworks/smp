@extends('layouts.admin')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-indigo-600">Profil Akun</p>
                <h2 class="mt-2 text-2xl font-bold text-gray-900">Kelola informasi akun Anda</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Perbarui data profil, ubah password, dan atur keamanan akun dari satu halaman yang sama.
                </p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
