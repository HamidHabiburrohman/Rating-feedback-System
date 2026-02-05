<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 9px; color: #1e293b; background-color: #ffffff; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; }
        .logo { margin-bottom: 10px; }
        .logo img { height: 50px; }
        .title { font-size: 18px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
        .date { color: #64748b; margin-top: 5px; }
        .summary-card { background-color: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .summary-item { margin-bottom: 4px; }
        .summary-label { font-weight: bold; color: #475569; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        th { background-color: #1e293b; color: #ffffff; padding: 10px 8px; text-align: left; font-weight: 600; text-transform: uppercase; font-size: 8px; }
        td { padding: 8px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: top; }
        tr:nth-child(even) { background-color: #f8fafc; }
        tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('assets/images/logos/logo1.svg') }}" alt="Logo">
        </div>
        <div class="title">{{ $title }}</div>
        <div class="date">Generated: {{ date('d/m/Y H:i') }}</div>
    </div>
    
    @if(isset($options['summary']))
    <div class="summary-card">
        @foreach($options['summary'] as $label => $value)
            <div class="summary-item">
                <span class="summary-label">{{ $label }}:</span> <span>{{ $value }}</span>
            </div>
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