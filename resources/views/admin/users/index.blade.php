@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-7xl">
    <div id="admin-users-live-container">
        @include('admin.users._index-content')
    </div>
</div>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function confirmDelete(id)
{
    Swal.fire({
        title: 'Hapus User?',
        text: 'Data user tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true

    }).then((result) => {

        if (result.isConfirmed) {

            document.getElementById('delete-form-' + id).submit();

        }

    });
}

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('admin-users-live-container');

    if (!container) {
        return;
    }

    let debounceTimer = null;
    let activeController = null;

    function bindLiveSearch() {
        const searchInput = container.querySelector('.admin-live-search-input');
        const searchForm = container.querySelector('.admin-live-search-form');
        const paginationLinks = container.querySelectorAll('.pagination a');

        searchForm?.addEventListener('submit', function (event) {
            event.preventDefault();
            fetchContent(buildUrl(searchInput?.value ?? ''));
        });

        searchInput?.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                fetchContent(buildUrl(searchInput.value));
            }, 300);
        });

        paginationLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                fetchContent(link.href);
            });
        });
    }

    function buildUrl(searchValue) {
        const url = new URL(window.location.href);

        if (searchValue.trim() === '') {
            url.searchParams.delete('search');
        } else {
            url.searchParams.set('search', searchValue.trim());
        }

        url.searchParams.delete('page');

        return url.toString();
    }

    function fetchContent(url) {
        activeController?.abort();
        activeController = new AbortController();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            signal: activeController.signal,
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Gagal memuat data.');
                }

                return response.text();
            })
            .then(function (html) {
                container.innerHTML = html;
                window.history.replaceState({}, '', url);
                bindLiveSearch();
            })
            .catch(function (error) {
                if (error.name !== 'AbortError') {
                    console.error(error);
                }
            });
    }

    bindLiveSearch();
});

</script>

@endsection
