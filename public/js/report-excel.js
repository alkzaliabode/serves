/* Local XLSX export for report pages. No network or third-party scripts required. */
(function () {
    'use strict';
    const encoder = new TextEncoder();
    const xml = value => String(value ?? '').replace(/[\u0000-\u0008\u000b\u000c\u000e-\u001f]/g, '')
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    // XLSX is a ZIP of XML files. Store entries without compression for offline use.
    function zip(files) {
        const parts = [], directory = [];
        let offset = 0, directorySize = 0;
        for (const [path, content] of Object.entries(files)) {
            const name = encoder.encode(path), data = encoder.encode(content);
            let crc = 0xffffffff;
            for (const byte of data) {
                crc ^= byte;
                for (let bit = 0; bit < 8; bit++) crc = (crc >>> 1) ^ ((crc & 1) ? 0xedb88320 : 0);
            }
            crc = (crc ^ 0xffffffff) >>> 0;
            const local = new Uint8Array(30), l = new DataView(local.buffer);
            l.setUint32(0, 0x04034b50, true); l.setUint16(4, 20, true);
            l.setUint16(12, 33, true); l.setUint32(14, crc, true);
            l.setUint32(18, data.length, true); l.setUint32(22, data.length, true);
            l.setUint16(26, name.length, true);
            parts.push(local, name, data);
            const central = new Uint8Array(46), c = new DataView(central.buffer);
            c.setUint32(0, 0x02014b50, true); c.setUint16(4, 20, true); c.setUint16(6, 20, true);
            c.setUint16(14, 33, true); c.setUint32(16, crc, true);
            c.setUint32(20, data.length, true); c.setUint32(24, data.length, true);
            c.setUint16(28, name.length, true); c.setUint32(42, offset, true);
            directory.push(central, name);
            directorySize += central.length + name.length;
            offset += local.length + name.length + data.length;
        }
        const end = new Uint8Array(22), e = new DataView(end.buffer);
        e.setUint32(0, 0x06054b50, true);
        e.setUint16(8, directory.length / 2, true); e.setUint16(10, directory.length / 2, true);
        e.setUint32(12, directorySize, true); e.setUint32(16, offset, true);
        return new Blob([...parts, ...directory, end], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    }

    function workbook(rows) {
        const ns = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
        const sheet = rows.map((row, index) => '<row r="' + (index + 1) + '">' + row.map(value => {
            // Explicit strings prevent formula injection and preserve employee IDs and leading zeros.
            if (typeof value === 'number' && Number.isFinite(value)) return '<c><v>' + value + '</v></c>';
            return '<c t="inlineStr"><is><t xml:space="preserve">' + xml(value) + '</t></is></c>';
        }).join('') + '</row>').join('');
        return zip({
            '[Content_Types].xml': '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
            '_rels/.rels': '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
            'xl/workbook.xml': '<workbook xmlns="' + ns + '" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="التقرير" sheetId="1" r:id="rId1"/></sheets></workbook>',
            'xl/_rels/workbook.xml.rels': '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
            'xl/worksheets/sheet1.xml': '<worksheet xmlns="' + ns + '"><sheetViews><sheetView workbookViewId="0" rightToLeft="1"/></sheetViews><sheetData>' + sheet + '</sheetData></worksheet>'
        });
    }

    function tableRows() {
        const table = document.getElementById('excel-report-table');
        if (!table || !table.tBodies[0] || !table.tBodies[0].rows.length) throw new Error('empty');
        const count = table.tHead.rows[0].cells.length - (table.dataset.excludeLast === 'true' ? 1 : 0);
        return Array.from(table.rows).filter(row => row.cells.length >= count).map(row =>
            Array.from(row.cells).slice(0, count).map((cell, column) => {
                const images = Array.from(cell.querySelectorAll('a')).filter(link => link.querySelector('img'));
                if (images.length) return Array.from(images, link => link.href).join('\n');
                const value = cell.textContent.replace(/\s+/g, ' ').trim();
                const textColumns = (table.dataset.textColumns || '').split(',');
                return !textColumns.includes(String(column)) && /^-?(0|[1-9]\d*)(\.\d+)?$/.test(value)
                    && Number.isSafeInteger(Number(value.split('.')[0])) ? Number(value) : value;
            }));
    }

    async function download(button, filename, report) {
        button.disabled = true;
        try {
            let rows;
            if (report) {
                const url = new URL(report, window.location.href);
                url.search = window.location.search;
                url.searchParams.delete('page');
                const response = await fetch(url, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('export');
                const data = await response.json();
                if (!data.rows.length) throw new Error('empty');
                rows = [data.headers, ...data.rows];
            } else {
                rows = tableRows();
                if (rows.length < 2) throw new Error('empty');
            }
            const url = URL.createObjectURL(workbook(rows));
            const link = document.createElement('a');
            link.href = url;
            link.download = filename + '_' + new Date().toISOString().slice(0, 10) + '.xlsx';
            document.body.appendChild(link); link.click(); link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        } catch (error) {
            window.alert(error.message === 'empty' ? 'لا توجد بيانات للتصدير.' : 'تعذر تصدير التقرير. يرجى المحاولة مرة أخرى.');
        } finally {
            button.disabled = false;
        }
    }
    window.ReportExcel = { download };
}());
