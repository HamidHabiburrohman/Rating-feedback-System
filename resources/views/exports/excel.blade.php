{{-- resources/views/exports/print.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { font-size: 12pt; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
        }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .date { font-size: 12px; color: #666; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; padding: 8px; text-align: left; border: 1px solid #ddd; }
        td { padding: 6px; border: 1px solid #ddd; }
        .no-print { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer;">
            🖨️ Print
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #f44336; color: white; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>
    
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="date">Printed on: {{ now()->format('d F Y H:i:s') }}</div>
    </div>
    
    @if(isset($options['summary']) && !empty($options['summary']))
    <div style="margin-bottom: 15px; padding: 10px; background-color: #f9f9f9;">
        @foreach($options['summary'] as $label => $value)
            <div><strong>{{ $label }}:</strong> {{ $value }}</div>
        @endforeach
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                @foreach($columns as $column)
                <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                @foreach($row as $cell)
                <td>{{ $cell }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>