@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Edit {{ $resource['label'] }}
            </h1>

            <p class="mt-1 text-gray-500">
                Perbarui data {{ strtolower($resource['label']) }}
            </p>
        </div>

        <a
            href="{{ route($resource['route_name'] . '.index') }}"
            class="inline-flex items-center px-4 py-2 font-semibold text-white bg-gray-600 rounded-xl hover:bg-gray-700"
        >
            Kembali
        </a>
    </div>

    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
        <form id="update-form" method="POST" action="{{ route($resource['route_name'] . '.update', $item->getKey()) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.resources._form', ['item' => $item])
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('update-form');

    if (!form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        if (form.dataset.submitted === 'true') {
            return;
        }

        event.preventDefault();

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Perubahan data akan diperbarui ke database.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.dataset.submitted = 'true';
                form.submit();
            }
        });
    });
});
</script>
@endsection
