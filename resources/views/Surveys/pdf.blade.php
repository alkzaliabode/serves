<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>استبيانات رضا الزائرين</title>
    <style>
        @page { size: A4; margin: 12mm; }
        body { font-family: Tahoma, Arial, sans-serif; color: #172b4d; margin: 20px auto; max-width: 960px; }
        .tools { padding: 16px; background: #eef4fa; margin-bottom: 20px; }
        button { padding: 10px 20px; cursor: pointer; }
        h1 { font-size: 21px; } h2 { font-size: 17px; }
        table { border-collapse: collapse; width: 100%; font-size: 12px; }
        th, td { border: 1px solid #ccd5df; padding: 7px; text-align: right; white-space: pre-wrap; overflow-wrap: anywhere; }
        th { width: 38%; background: #eef4fa; }
        tr { break-inside: avoid; }
        article { margin-bottom: 30px; }
        @media print {
            body { margin: 0; max-width: none; }
            .tools { display: none; }
            article { break-after: page; margin: 0; }
            article:last-child { break-after: auto; }
        }
    </style>
</head>
<body>
    <div class="tools">
        <p>لحفظ الملف بصيغة PDF، اختر «حفظ بتنسيق PDF» من نافذة الطباعة. عدد الاستبيانات: {{ count($rows) }}.</p>
        <button type="button" onclick="window.print()">حفظ PDF / طباعة</button>
    </div>
    @forelse ($rows as $row)
        <article>
            <h1>استبيان رضا الزائرين</h1>
            <h2>الاستبيان {{ $loop->iteration }} من {{ count($rows) }}</h2>
            <table>
                <tbody>
                @foreach ($headers as $index => $header)
                    <tr><th scope="row">{{ $header }}</th><td>{{ $row[$index] }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </article>
    @empty
        <p>لا توجد استبيانات مطابقة للفلاتر المحددة.</p>
    @endforelse
</body>
</html>
