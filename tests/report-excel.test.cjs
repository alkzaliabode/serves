const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

function setup(data, table) {
    let blob, requested, filename;
    const alerts = [];
    class TestURL extends URL {
        static createObjectURL(value) { blob = value; return 'blob:test'; }
        static revokeObjectURL() {}
    }
    const context = {
        TextEncoder, Uint8Array, DataView, Blob, URL: TestURL, setTimeout: fn => fn(),
        window: { location: { href: 'http://localhost/monthly-cleaning-report?month=2026-11&page=3&sort_order=asc', search: '?month=2026-11&page=3&sort_order=asc' }, alert: text => alerts.push(text) },
        document: {
            getElementById: () => table,
            body: { appendChild() {} },
            createElement: () => ({ click() { filename = this.download; }, remove() {} }),
        },
        fetch: async url => { requested = url; return { ok: true, json: async () => data }; },
    };
    vm.runInNewContext(fs.readFileSync('public/js/report-excel.js', 'utf8'), context);
    return { api: context.window.ReportExcel, alerts, blob: () => blob, requested: () => requested, filename: () => filename };
}

// Read stored ZIP entries and check CRCs, sizes and the central directory.
async function entries(blob) {
    const bytes = Buffer.from(await blob.arrayBuffer());
    const files = {};
    let pos = 0;
    while (bytes.readUInt32LE(pos) === 0x04034b50) {
        const size = bytes.readUInt32LE(pos + 18), nameLength = bytes.readUInt16LE(pos + 26);
        const name = bytes.subarray(pos + 30, pos + 30 + nameLength).toString();
        const content = bytes.subarray(pos + 30 + nameLength, pos + 30 + nameLength + size);
        let crc = 0xffffffff;
        for (const byte of content) {
            crc ^= byte;
            for (let bit = 0; bit < 8; bit++) crc = (crc >>> 1) ^ ((crc & 1) ? 0xedb88320 : 0);
        }
        assert.equal((crc ^ 0xffffffff) >>> 0, bytes.readUInt32LE(pos + 14));
        files[name] = content.toString();
        pos += 30 + nameLength + size;
    }
    const end = bytes.length - 22;
    assert.equal(bytes.readUInt32LE(end), 0x06054b50);
    assert.equal(bytes.readUInt32LE(end + 16), pos);
    assert.equal(bytes.readUInt16LE(end + 10), Object.keys(files).length);
    return files;
}

test('exports all server rows, preserves filters, Arabic, numbers and safe strings', async () => {
    const data = { headers: ['الاسم', 'العدد'], rows: Array.from({ length: 25 }, (_, i) => ['عربي & <نص> =1+1', i]) };
    const env = setup(data), button = { disabled: false };
    await env.api.download(button, 'report', '/monthly-cleaning-report/export');
    assert.equal(button.disabled, false);
    assert.equal(env.requested().searchParams.get('month'), '2026-11');
    assert.equal(env.requested().searchParams.get('sort_order'), 'asc');
    assert.equal(env.requested().searchParams.has('page'), false);
    assert.match(env.filename(), /\.xlsx$/);
    const files = await entries(env.blob());
    assert.equal(Object.keys(files).length, 5);
    const sheet = files['xl/worksheets/sheet1.xml'];
    assert.equal((sheet.match(/<row /g) || []).length, 26);
    assert.match(sheet, /عربي &amp; &lt;نص&gt; =1\+1/);
    assert.match(sheet, /<c><v>24<\/v><\/c>/);
    assert.match(sheet, /rightToLeft="1"/);
    assert.deepEqual(env.alerts, []);
});

test('table export excludes actions and exports image URLs and employee IDs as text', async () => {
    const cell = (text, links = []) => ({ textContent: text, querySelectorAll: () => links });
    const header = { cells: [cell('الاسم'), cell('الصور'), cell('الرقم'), cell('إجراءات')] };
    const row = { cells: [cell('اسم'), cell('', [{ href: 'http://localhost/image.jpg', querySelector: () => ({}) }]), cell('00123'), cell('حذف')] };
    const env = setup(null, { dataset: { excludeLast: 'true', textColumns: '2' }, tHead: { rows: [header] }, tBodies: [{ rows: [row] }], rows: [header, row] });
    await env.api.download({ disabled: false }, 'photos');
    const files = await entries(env.blob()), sheet = files['xl/worksheets/sheet1.xml'];
    assert.match(sheet, /http:\/\/localhost\/image.jpg/);
    assert.match(sheet, /00123/);
    assert.doesNotMatch(sheet, /حذف|إجراءات/);
});

test('empty reports show a message and restore the button', async () => {
    const env = setup({ headers: [], rows: [] }), button = { disabled: false };
    await env.api.download(button, 'empty', '/report/export');
    assert.equal(env.blob(), undefined);
    assert.equal(env.alerts.length, 1);
    assert.equal(button.disabled, false);
});
