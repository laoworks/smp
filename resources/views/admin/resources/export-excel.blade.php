<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export {{ $resource['label'] }}</title>
</head>
<body>
    <table border="1">
        <tr>
            <td colspan="{{ count($fields) + 1 }}"><strong>Data {{ $resource['label'] }}</strong></td>
        </tr>
        <tr>
            <td colspan="{{ count($fields) + 1 }}">Diekspor pada: {{ $exportedAt->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <th>No</th>
            @foreach($fields as $field)
                <th>{{ $field['label'] }}</th>
            @endforeach
        </tr>
        @forelse($rows as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                @foreach($row['values'] as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($fields) + 1 }}">Tidak ada data untuk diekspor.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>
