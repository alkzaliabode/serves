/* Read the report's print view without executing its scripts. This keeps Excel
   columns, filters and summaries in sync with the printable report. */
(function () {
    'use strict';
    const text = node => (node?.textContent || '').replace(/\s+/g, ' ').trim();
    const column = n => {
        let result = '';
        for (n++; n; n = Math.floor((n - 1) / 26)) result = String.fromCharCode(65 + (n - 1) % 26) + result;
        return result;
    };

    async function imageData(source) {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        await new Promise((resolve, reject) => {
            const timer = setTimeout(() => reject(new Error('image')), 20000);
            img.onload = () => { clearTimeout(timer); resolve(); };
            img.onerror = () => { clearTimeout(timer); reject(new Error('image')); };
            img.src = source;
        });
        const scale = Math.min(1, 380 / img.naturalWidth, 270 / img.naturalHeight);
        const canvas = document.createElement('canvas');
        canvas.width = Math.round(img.naturalWidth * scale);
        canvas.height = Math.round(img.naturalHeight * scale);
        canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
        if (!blob) throw new Error('image');
        return { bytes: new Uint8Array(await blob.arrayBuffer()), width: canvas.width, height: canvas.height };
    }

    async function parse(doc, sourceUrl) {
        const table = doc.querySelector('table');
        const photo = doc.querySelector('.info-section');
        if (!photo && !table?.tBodies[0]?.rows.length) throw new Error('empty');
        const headers = table ? Array.from(table.tHead.rows[0].cells, text) : [];
        const count = photo ? 4 : headers.length;
        const rows = [], styles = {}, merges = [], heights = {}, images = [];
        const full = (value, style = 0) => {
            const index = rows.length;
            rows.push([value]); styles[index] = style;
            merges.push('A' + (index + 1) + ':' + column(count - 1) + (index + 1));
            heights[index] = style === 3 ? 36 : Math.max(28, Math.ceil(value.length / (count * 12)) * 18);
        };
        const title = text(doc.querySelector('.title, .title-print'));
        if (!title) throw new Error('export');
        full(title, 3);
        doc.querySelectorAll('.header .subtitle, .header-print .subtitle-print, .header-print .print-date, .header .filters-display').forEach(node => {
            if (text(node)) full(text(node));
        });
        rows.push([]);
        if (table) {
            styles[rows.length] = 2;
            rows.push(headers);
            for (const row of table.tBodies[0].rows) {
                if (row.cells.length !== count) continue;
                rows.push(Array.from(row.cells, (cell, i) => {
                    const value = text(cell);
                    const numeric = /الكمية|المقاعد|المرايا|الخلاطات|الأبواب|المغاسل|الحمامات|المنادر|الوسائد|المراوح|النوافذ|السجاد|البطانيات|الأسرة|المستفيدون|الترامز|حاويات|ساعات العمل|متوسط التقييم/.test(headers[i]);
                    return numeric && /^-?\d+(\.\d+)?$/.test(value) ? Number(value) : value;
                }));
            }
            const summary = doc.querySelector('.summary-section');
            if (summary) {
                rows.push([]);
                full(text(summary.querySelector('h4')), 4);
                summary.querySelectorAll('.summary-item').forEach(item => {
                    const label = text(item.querySelector('span'));
                    const value = text(item.querySelector('strong'));
                    styles[rows.length] = 4;
                    const values = Array(count).fill('');
                    values[0] = label;
                    values[count - 1] = /^-?\d+(\.\d+)?$/.test(value) ? Number(value) : value;
                    rows.push(values);
                    if (count > 2) merges.push('A' + rows.length + ':' + column(count - 2) + rows.length);
                });
            }
            const total = text(doc.querySelector('.total-summary'));
            if (total) { rows.push([]); full(total, 4); }
        } else {
            photo.querySelectorAll('p').forEach(p => {
                rows.push([text(p.querySelector('strong')), text(p.querySelector('span'))]);
                merges.push('B' + rows.length + ':D' + rows.length);
            });
            rows.push([]);
            full(text(doc.querySelector('.notes-section h4')), 4);
            full(text(doc.querySelector('.notes-section p')));
            for (const pair of doc.querySelectorAll('.image-pair-container')) {
                rows.push([]);
                const items = Array.from(pair.querySelectorAll('.image-pair-item')).reverse();
                styles[rows.length] = 2;
                rows.push([text(items[0]?.querySelector('.image-label')), '', text(items[1]?.querySelector('.image-label'))]);
                merges.push('A' + rows.length + ':B' + rows.length, 'C' + rows.length + ':D' + rows.length);
                const index = rows.length;
                rows.push(['', '', '', '']); heights[index] = 220;
                for (let i = 0; i < items.length; i++) {
                    const img = items[i].querySelector('img');
                    if (img && !img.classList.contains('placeholder-image-print')) {
                        images.push({ ...await imageData(new URL(img.getAttribute('src'), sourceUrl).href), row: index, column: i * 2 });
                    } else rows[index][i * 2] = text(items[i].querySelector('.caption-print')) || 'لا توجد صورة';
                }
            }
        }
        const footer = doc.querySelector('.footer');
        if (footer) {
            const copy = footer.cloneNode(true);
            // Browser page numbering belongs to PDF, not the spreadsheet.
            copy.querySelectorAll('br').forEach(br => { while (br.nextSibling) br.nextSibling.remove(); br.remove(); });
            rows.push([]); full(text(copy));
        }
        const widths = photo ? [28, 28, 28, 28] : headers.map(h => /ملاحظات|الملاحظات/.test(h) ? 60 : /الاسم|المورد|الوحدة|الموقع|المنشأة|البريد|المسمى/.test(h) ? 28 : 18);
        return { rows, options: { styled: true, styles, merges, heights, widths, images } };
    }

    async function download(button, filename, report, preserveQuery = true) {
        button.disabled = true;
        try {
            const reports = Array.isArray(report) ? report : [report];
            if (!reports.length) throw new Error('empty');
            let data;
            for (const address of reports) {
                const url = new URL(address, window.location.href);
                if (preserveQuery) {
                    const parameters = new URLSearchParams(window.location.search);
                    parameters.delete('page');
                    parameters.forEach((value, key) => url.searchParams.set(key, value));
                }
                const response = await fetch(url, { headers: { Accept: 'text/html' } });
                if (!response.ok || response.redirected) throw new Error('export');
                const doc = new DOMParser().parseFromString(await response.text(), 'text/html');
                const part = await parse(doc, url.href);
                if (!data) data = part;
                else {
                    const offset = data.rows.length + 2;
                    data.rows.push([], [], ...part.rows);
                    for (const key of ['styles', 'heights']) {
                        Object.entries(part.options[key]).forEach(([row, value]) => { data.options[key][Number(row) + offset] = value; });
                    }
                    data.options.merges.push(...part.options.merges.map(range => range.replace(/\d+/g, row => String(Number(row) + offset))));
                    data.options.images.push(...part.options.images.map(img => ({ ...img, row: img.row + offset })));
                }
            }
            const blobUrl = URL.createObjectURL(ReportExcel.workbook(data.rows, data.options));
            const link = document.createElement('a');
            link.href = blobUrl;
            link.download = filename + '_' + new Date().toISOString().slice(0, 10) + '.xlsx';
            document.body.appendChild(link); link.click(); link.remove();
            setTimeout(() => URL.revokeObjectURL(blobUrl), 1000);
        } catch (error) {
            window.alert(error.message === 'empty' ? 'لا توجد بيانات للتصدير.' : error.message === 'image' ? 'تعذر تحميل إحدى صور التقرير. يرجى التحقق من الصور والمحاولة مجدداً.' : 'تعذر تصدير التقرير. يرجى المحاولة مرة أخرى.');
        } finally { button.disabled = false; }
    }
    const downloadPhotos = button => download(button, 'التقارير_المصورة', Array.from(document.querySelectorAll('[data-print-excel]'), node => node.dataset.printExcel), false);
    window.ReportPrintExcel = { download, downloadPhotos };
}());
