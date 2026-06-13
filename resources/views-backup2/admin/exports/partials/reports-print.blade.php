<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Export - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th {
            background: #f1f5f9;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }
        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 11px;
            color: #94a3b8;
        }
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reports Export</h1>
        <p>Generated on {{ now()->format('d F Y H:i') }}</p>
        @if(!empty($filters))
            <p style="font-size: 11px;">
                Filters: 
                @foreach($filters as $key => $value)
                    @if($value)
                        {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }} | 
                    @endif
                @endforeach
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Tracking Code</th>
                <th>Title</th>
                <th>Unit</th>
                <th>Student</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Admin Response</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr>
                <td>{{ $report->tracking_code }}</td>
                <td>{{ $report->title }}</td>
                <td>{{ $report->unit->name ?? '-' }}</td>
                <td>{{ $report->student->name ?? '-' }}</td>
                <td>{{ ucfirst($report->priority) }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($report->status)) }}</td>
                <td>{{ Str::limit($report->admin_response ?? '-', 30) }}</td>
                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">No reports found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total Records: {{ $reports->count() }}
    </div>
</body>
</html>