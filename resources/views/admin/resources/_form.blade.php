@php
    $item = $item ?? null;
@endphp

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    @foreach($resource['fields'] as $field)
        @php
            $value = $item ? $manager->fieldValue($item, $field) : null;
            $oldValue = old($field['name'], $value);
        @endphp

        <div class="{{ $field['full_width'] ? 'md:col-span-2' : '' }}">
            <label for="{{ $field['name'] }}" class="block mb-2 text-sm font-semibold text-gray-700">
                {{ $field['label'] }}
                @if($field['required'])
                    <span class="text-red-500">*</span>
                @endif
            </label>

            @if($field['type'] === 'textarea')
                @if($field['rich_text'])
                    <textarea
                        id="{{ $field['name'] }}"
                        name="{{ $field['name'] }}"
                        class="hidden js-rich-text-input"
                    >{{ $oldValue }}</textarea>

                    <div class="js-rich-text-wrapper">
                        <div id="toolbar-{{ $field['name'] }}" class="js-rich-text-toolbar">
                            <span class="ql-formats">
                                <select class="ql-header">
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option selected></option>
                                </select>
                            </span>
                            <span class="ql-formats">
                                <button type="button" class="ql-bold"></button>
                                <button type="button" class="ql-italic"></button>
                                <button type="button" class="ql-underline"></button>
                            </span>
                            <span class="ql-formats">
                                <button type="button" class="ql-list" value="ordered"></button>
                                <button type="button" class="ql-list" value="bullet"></button>
                            </span>
                            <span class="ql-formats">
                                <button type="button" class="ql-link"></button>
                                <button type="button" class="ql-blockquote"></button>
                            </span>
                            <span class="ql-formats">
                                <button type="button" class="ql-clean"></button>
                            </span>
                        </div>

                        <div
                            class="js-rich-text-editor"
                            data-input-id="{{ $field['name'] }}"
                            data-toolbar-id="toolbar-{{ $field['name'] }}"
                        ></div>
                    </div>
                @else
                    <textarea
                        id="{{ $field['name'] }}"
                        name="{{ $field['name'] }}"
                        rows="5"
                        placeholder="{{ $field['placeholder'] }}"
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ $oldValue }}</textarea>
                @endif
            @elseif($field['type'] === 'select' || $field['type'] === 'relation')
                <select
                    id="{{ $field['name'] }}"
                    name="{{ $field['name'] }}"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Pilih {{ strtolower($field['label']) }}</option>
                    @foreach($field['options'] as $option)
                        <option value="{{ $option['value'] }}" @selected((string) $oldValue === (string) $option['value'])>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
            @elseif($field['type'] === 'boolean')
                <input type="hidden" name="{{ $field['name'] }}" value="0">
                <label class="flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                    <input
                        id="{{ $field['name'] }}"
                        type="checkbox"
                        name="{{ $field['name'] }}"
                        value="1"
                        class="text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        @checked((bool) old($field['name'], $item ? (bool) $item->{$field['name']} : false))
                    >
                    <span class="text-sm text-gray-700">{{ $field['toggle_text'] }}</span>
                </label>
            @elseif($field['type'] === 'file')
                @if($item && filled($item->{$field['name']}))
                    <div class="p-3 mb-3 border border-gray-200 rounded-xl bg-gray-50">
                        @if($field['is_image'])
                            <img
                                src="{{ asset('storage/' . $item->{$field['name']}) }}"
                                alt="{{ $field['label'] }}"
                                class="object-cover w-24 h-24 border border-gray-200 rounded-xl"
                            >
                        @else
                            <p class="text-sm text-gray-600">{{ basename($item->{$field['name']}) }}</p>
                        @endif
                    </div>
                @endif

                <input
                    id="{{ $field['name'] }}"
                    type="file"
                    name="{{ $field['name'] }}"
                    @if($field['accept']) accept="{{ $field['accept'] }}" @endif
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            @else
                <input
                    id="{{ $field['name'] }}"
                    type="{{ $field['type'] === 'datetime' ? 'datetime-local' : $field['type'] }}"
                    name="{{ $field['name'] }}"
                    value="{{ $oldValue }}"
                    @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                    @if($field['step']) step="{{ $field['step'] }}" @endif
                    @if($field['min']) min="{{ $field['min'] }}" @endif
                    @if($field['max']) max="{{ $field['max'] }}" @endif
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            @endif

            @error($field['name'])
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endforeach
</div>

<div class="flex items-center justify-end gap-3 mt-8">
    <a
        href="{{ route($resource['route_name'] . '.index') }}"
        class="px-5 py-3 font-semibold text-gray-700 transition bg-gray-100 rounded-xl hover:bg-gray-200"
    >
        Batal
    </a>

    <button
        type="submit"
        class="px-5 py-3 font-semibold text-white transition bg-indigo-600 rounded-xl shadow hover:bg-indigo-700"
    >
        Simpan
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Quill === 'undefined') {
        return;
    }

    document.querySelectorAll('.js-rich-text-editor').forEach(function (editorElement) {
        if (editorElement.dataset.initialized === 'true') {
            return;
        }

        const inputId = editorElement.dataset.inputId;
        const input = document.getElementById(inputId);

        if (!input) {
            return;
        }

        const quill = new Quill(editorElement, {
            theme: 'snow',
            placeholder: 'Tulis isi konten di sini...',
            modules: {
                toolbar: '#' + editorElement.dataset.toolbarId
            }
        });

        const editor = editorElement.querySelector('.ql-editor');

        if (editorElement) {
            editorElement.style.height = '260px';
            editorElement.style.minHeight = '260px';
        }

        if (editor) {
            editor.style.minHeight = '260px';
        }

        quill.root.innerHTML = input.value || '';
        editorElement.dataset.initialized = 'true';

        const form = editorElement.closest('form');

        if (form && form.dataset.richTextBound !== 'true') {
            form.addEventListener('submit', function () {
                document.querySelectorAll('.js-rich-text-editor').forEach(function (currentEditor) {
                    const currentInput = document.getElementById(currentEditor.dataset.inputId);

                    if (!currentInput || !currentEditor.__quill) {
                        return;
                    }

                    const html = currentEditor.__quill.root.innerHTML;
                    currentInput.value = html === '<p><br></p>' ? '' : html;
                });
            });

            form.dataset.richTextBound = 'true';
        }

        editorElement.__quill = quill;
    });
});
</script>
