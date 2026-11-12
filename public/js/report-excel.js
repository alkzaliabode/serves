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
            const name = encoder.encode(path), data = content instanceof Uint8Array ? content : encoder.encode(content);
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

    function workbook(rows, options = {}) {
        const ns = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
        const columnName = index => {
            let name = '';
            for (index++; index; index = Math.floor((index - 1) / 26)) name = String.fromCharCode(65 + (index - 1) % 26) + name;
            return name;
        };
        const sheet = rows.map((row, index) => '<row r="' + (index + 1) + '"' + (options.heights?.[index] ? ' ht="' + options.heights[index] + '" customHeight="1"' : '') + '>' + row.map((value, col) => {
            const attributes = ' r="' + columnName(col) + (index + 1) + '"' + (options.styled ? ' s="' + (options.styles?.[index] ?? 1) + '"' : '');
            // Explicit strings prevent formula injection and preserve employee IDs and leading zeros.
            if (typeof value === 'number' && Number.isFinite(value)) return '<c' + attributes + '><v>' + value + '</v></c>';
            return '<c' + attributes + ' t="inlineStr"><is><t xml:space="preserve">' + xml(value) + '</t></is></c>';
        }).join('') + '</row>').join('');
        const files = {
            '[Content_Types].xml': '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
            '_rels/.rels': '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
            'xl/workbook.xml': '<workbook xmlns="' + ns + '" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="التقرير" sheetId="1" r:id="rId1"/></sheets></workbook>',
            'xl/_rels/workbook.xml.rels': '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
            'xl/worksheets/sheet1.xml': '<worksheet xmlns="' + ns + '"><sheetViews><sheetView workbookViewId="0" rightToLeft="1"/></sheetViews><sheetData>' + sheet + '</sheetData></worksheet>'
        };
        if (options.styled) {
            files['[Content_Types].xml'] = files['[Content_Types].xml'].replace('</Types>', '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>');
            files['xl/_rels/workbook.xml.rels'] = files['xl/_rels/workbook.xml.rels'].replace('</Relationships>', '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
            const borders = '<border><left style="thin"><color rgb="FFD9E2F3"/></left><right style="thin"><color rgb="FFD9E2F3"/></right><top style="thin"><color rgb="FFD9E2F3"/></top><bottom style="thin"><color rgb="FFD9E2F3"/></bottom></border>';
            files['xl/styles.xml'] = '<styleSheet xmlns="' + ns + '"><fonts count="3"><font><sz val="11"/><name val="Arial"/></font><font><b/><sz val="12"/><name val="Arial"/></font><font><b/><sz val="16"/><name val="Arial"/></font></fonts><fills count="4"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD9EAF7"/><bgColor indexed="64"/></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFE2EFDA"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="2"><border/>' + borders + '</borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="5">' + [[0,0,0],[0,0,1],[1,2,1],[2,0,0],[1,3,1]].map(([font,fill,border]) => '<xf numFmtId="0" fontId="' + font + '" fillId="' + fill + '" borderId="' + border + '" xfId="0" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1" readingOrder="2"/></xf>').join('') + '</cellXfs></styleSheet>';
            const cols = options.widths.map((width, i) => '<col min="' + (i + 1) + '" max="' + (i + 1) + '" width="' + width + '" customWidth="1"/>').join('');
            const merges = options.merges?.length ? '<mergeCells count="' + options.merges.length + '">' + options.merges.map(range => '<mergeCell ref="' + range + '"/>').join('') + '</mergeCells>' : '';
            files['xl/worksheets/sheet1.xml'] = '<worksheet xmlns="' + ns + '" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheetViews><sheetView workbookViewId="0" rightToLeft="1"/></sheetViews><sheetFormatPr defaultRowHeight="30"/><cols>' + cols + '</cols><sheetData>' + sheet + '</sheetData>' + merges + '<printOptions horizontalCentered="1"/><pageMargins left="0.25" right="0.25" top="0.4" bottom="0.4" header="0.2" footer="0.2"/><pageSetup paperSize="9" orientation="landscape" fitToWidth="1" fitToHeight="0"/>' + (options.images?.length ? '<drawing r:id="rId1"/>' : '') + '</worksheet>';
        }
        if (options.images?.length) {
            files['[Content_Types].xml'] = files['[Content_Types].xml'].replace('</Types>', '<Default Extension="png" ContentType="image/png"/><Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/></Types>');
            files['xl/worksheets/_rels/sheet1.xml.rels'] = '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml"/></Relationships>';
            files['xl/drawings/_rels/drawing1.xml.rels'] = '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' + options.images.map((img,i) => '<Relationship Id="rId' + (i+1) + '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="../media/image' + (i+1) + '.png"/>').join('') + '</Relationships>';
            files['xl/drawings/drawing1.xml'] = '<xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">' + options.images.map((img,i) => {
                files['xl/media/image' + (i+1) + '.png'] = img.bytes;
                return '<xdr:oneCellAnchor><xdr:from><xdr:col>' + img.column + '</xdr:col><xdr:colOff>95250</xdr:colOff><xdr:row>' + img.row + '</xdr:row><xdr:rowOff>95250</xdr:rowOff></xdr:from><xdr:ext cx="' + Math.round(img.width*9525) + '" cy="' + Math.round(img.height*9525) + '"/><xdr:pic><xdr:nvPicPr><xdr:cNvPr id="' + (i+1) + '" name="صورة ' + (i+1) + '"/><xdr:cNvPicPr/></xdr:nvPicPr><xdr:blipFill><a:blip xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" r:embed="rId' + (i+1) + '"/><a:stretch><a:fillRect/></a:stretch></xdr:blipFill><xdr:spPr><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></xdr:spPr></xdr:pic><xdr:clientData/></xdr:oneCellAnchor>';
            }).join('') + '</xdr:wsDr>';
        }
        return zip(files);
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
    window.ReportExcel = { download, workbook };
}());
