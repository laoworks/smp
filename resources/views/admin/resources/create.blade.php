@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Tambah {{ $resource['label'] }}
            </h1>

            <p class="mt-1 text-gray-500">
                Form input data {{ strtolower($resource['label']) }}
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
        <form method="POST" action="{{ route($resource['route_name'] . '.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.resources._form')
        </form>
    </div>
</div>
@endsection
