<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export {{ $resource['label'] }}</title>
    <style>
        @page {
            margin: 18px 16px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #111827;
        }

        h1 {
            margin: 0 0 4px;
            font-size: 18px;
        }

        p {
            margin: 0 0 14px;
            color: #4b5563;
        }

        .section {
            margin-top: 18px;
        }

        .section:first-of-type {
            margin-top: 0;
        }

        .section-title {
            margin: 0 0 8px;
            font-size: 11px;
            font-weight: bold;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 5px 6px;
            vertical-align: top;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: anywhere;
            white-space: normal;
        }

        th {
            background: #f3f4f6;
            font-size: 8px;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tr {
            page-break-inside: avoid;
        }

        .no-col {
            width: 26px;
        }
    </style>
</head>
<body>
    @php
        $columnsPerTable = count($fields) > 12 ? 5 : (count($fields) > 8 ? 6 : 8);
        $fieldGroups = collect($fields)->chunk($columnsPerTable);
    @endphp

    <h1>Data {{ $resource['label'] }}</h1>
    <p>Diekspor pada {{ $exportedAt->format('d-m-Y H:i') }}</p>

    @forelse($fieldGroups as $groupIndex => $fieldGroup)
        <div class="section">
            @if($fieldGroups->count() > 1)
                <p class="section-title">Bagian {{ $groupIndex + 1 }} dari {{ $fieldGroups->count() }}</p>
            @endif

            <table>
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        @foreach($fieldGroup as $field)
                            <th>{{ $field['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row['no'] }}</td>
                            @foreach($fieldGroup as $field)
                                <td>{{ $row['mapped_values'][$field['name']] ?? '-' }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($fieldGroup) + 1 }}">Tidak ada data untuk diekspor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <table>
            <tbody>
                <tr>
                    <td>Tidak ada data untuk diekspor.</td>
                </tr>
            </tbody>
        </table>
    @endforelse
</body>
</html>
