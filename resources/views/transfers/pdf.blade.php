<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { size: A3 landscape; margin: 10mm; }
        body { font-family: Tahoma, Arial, sans-serif; color: #172b4d; padding: 20px; }
        h1 { font-size: 22px; } .tools { padding: 12px; background: #eef4fa; margin-bottom: 20px; }
        button { padding: 10px 20px; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; font-size: 11px; }
        th, td { border: 1px solid #bbc9d7; padding: 8px; text-align: right; white-space: pre-wrap; overflow-wrap: anywhere; }
        th { background: #eef4fa; } tr { break-inside: avoid; }
        .chart { width: 700px; height: 460px; margin: auto auto 25px; }
        @media print { .tools { display: none; } body { padding: 0; } .chart { break-inside: avoid; } }
    </style>
</head>
<body>
    <div class="tools"><p>اختر «حفظ بتنسيق PDF» من نافذة الطباعة. عدد السجلات: {{ count($rows) }}.</p><button onclick="window.print()">حفظ PDF / طباعة</button></div>
    <h1>{{ $title }}</h1>
    @if ($chart)
        <div class="chart"><canvas id="gilbert-export-chart"></canvas></div>
    @endif
    <table>
        <thead><tr>@foreach ($headers as $header)<th>{{ $header }}</th>@endforeach</tr></thead>
        <tbody>
        @forelse ($rows as $row)
            <tr>@foreach ($row as $value)<td>{{ $value }}</td>@endforeach</tr>
        @empty
            <tr><td colspan="{{ count($headers) }}">لا توجد بيانات مطابقة.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if ($chart)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chart = @json($chart);
            new Chart(document.getElementById('gilbert-export-chart'), {
                type: 'radar', data: { labels: chart.labels, datasets: chart.datasets },
                options: { animation: false, responsive: true, maintainAspectRatio: false,
                    scales: { r: { min: 0, max: 100 } }, plugins: { legend: { rtl: true } } }
            });
        </script>
    @endif
</body>
</html>
