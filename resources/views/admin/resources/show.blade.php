@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Detail {{ $resource['label'] }}
            </h1>

            <p class="mt-1 text-gray-500">
                Informasi lengkap {{ strtolower($resource['label']) }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="{{ route($resource['route_name'] . '.edit', $item->getKey()) }}"
                class="px-4 py-2 font-semibold text-white rounded-xl bg-amber-500 hover:bg-amber-600"
            >
                Edit
            </a>

            <a
                href="{{ route($resource['route_name'] . '.index') }}"
                class="px-4 py-2 font-semibold text-white bg-gray-600 rounded-xl hover:bg-gray-700"
            >
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
        @php
            $preview = $manager->getPreviewImageUrl($item, $resource);
        @endphp

        <div class="flex flex-col gap-6 mb-8 md:flex-row md:items-center">
            <div>
                @if($preview)
                    <img
                        src="{{ $preview }}"
                        alt="{{ $manager->getTitle($item, $resource) }}"
                        class="object-cover w-24 h-24 border border-gray-200 shadow-sm rounded-2xl"
                    >
                @else
                    <div class="flex items-center justify-center w-24 h-24 text-3xl font-bold text-indigo-700 bg-indigo-100 rounded-2xl">
                        {{ strtoupper(substr($manager->getTitle($item, $resource), 0, 1)) }}
                    </div>
                @endif
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $manager->getTitle($item, $resource) }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Data {{ strtolower($resource['label']) }} dengan ID #{{ $item->getKey() }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            @foreach($resource['fields'] as $field)
                <div class="{{ $field['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                    <div class="p-5 border border-gray-200 rounded-2xl bg-gray-50">
                        <p class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">
                            {{ $field['label'] }}
                        </p>

                        @if($field['type'] === 'file' && filled($item->{$field['name']}))
                            @if($field['is_image'])
                                <img
                                    src="{{ asset('storage/' . $item->{$field['name']}) }}"
                                    alt="{{ $field['label'] }}"
                                    class="object-cover w-40 h-40 rounded-xl"
                                >
                            @else
                                <p class="text-sm text-gray-800">{{ basename($item->{$field['name']}) }}</p>
                            @endif
                        @elseif($field['rich_text'])
                            <div class="text-sm leading-7 text-gray-800 prose prose-sm max-w-none">
                                {!! filled($item->{$field['name']}) ? $item->{$field['name']} : '-' !!}
                            </div>
                        @else
                            <p class="text-sm leading-7 text-gray-800 whitespace-pre-line">
                                {{ $manager->displayValue($item, $field) }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
