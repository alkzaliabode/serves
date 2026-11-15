const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

function setup(values, responseData, ok = true) {
    const requested = [], alerts = [], downloads = [];
    const form = { values };
    const doc = {
        querySelector(selector) {
            if (selector === 'table') return {
                tHead: { rows: [{ cells: [{ textContent: 'التقرير' }] }] },
                tBodies: [{ rows: [{ cells: [{ textContent: 'بيانات التقرير' }] }] }]
            };
            if (selector === '.title, .title-print') return { textContent: 'تقرير مصور' };
            return null;
        },
        querySelectorAll() { return []; }
    };
    class TestURL extends URL {
        static createObjectURL() { return 'blob:export'; }
        static revokeObjectURL() {}
    }
    const context = {
        URL: TestURL, URLSearchParams, setTimeout: fn => fn(),
        FormData: class { constructor(form) { this.values = form.values; } get(key) { return this.values[key] || ''; } },
        DOMParser: class { parseFromString() { return doc; } },
        window: { location: { href: 'http://localhost/photo_reports?from_date=2020-01-01&page=3', search: '?from_date=2020-01-01&page=3' }, alert: message => alerts.push(message) },
        document: {
            getElementById: () => form,
            querySelectorAll: () => { throw new Error('Must not export stale rows from the displayed table.'); },
            body: { appendChild() {} },
            createElement: () => ({ click() { downloads.push(this.download); }, remove() {} })
        },
        ReportExcel: { workbook: () => ({}) },
        fetch: async url => {
            requested.push(String(url));
            return requested.length === 1
                ? { ok, json: async () => responseData }
                : { ok: true, text: async () => '<html></html>' };
        }
    };
    vm.runInNewContext(fs.readFileSync('public/js/report-print-excel.js', 'utf8'), context);
    return { api: context.window.ReportPrintExcel, requested, alerts, downloads };
}

test('exports server matches for current form dates, even before applying the filter', async () => {
    const env = setup({ from_date: '2026-07-01', to_date: '2026-07-31' }, { reports: ['/photo_reports/2/print?print=1', '/photo_reports/7/print?print=1'] });
    const button = { dataset: { exportUrl: '/photo_reports/export-links' } };
    await env.api.downloadPhotos(button);
    const query = new URL(env.requested[0]).searchParams;
    assert.equal(query.get('from_date'), '2026-07-01');
    assert.equal(query.get('to_date'), '2026-07-31');
    assert.equal(query.has('page'), false);
    assert.equal(env.requested.length, 3);
    assert.equal(new URL(env.requested[1]).pathname, '/photo_reports/2/print');
    assert.equal(new URL(env.requested[2]).pathname, '/photo_reports/7/print');
    assert.equal(env.downloads.length, 1);
    assert.equal(env.alerts.length, 0);
    assert.equal(button.disabled, false);
});

test('empty matches produce a clear message and never export unfiltered rows', async () => {
    const env = setup({ to_date: '2026-01-01' }, { reports: [] });
    await env.api.downloadPhotos({ dataset: { exportUrl: '/photo_reports/export-links' } });
    assert.equal(env.requested.length, 1);
    assert.equal(env.downloads.length, 0);
    assert.match(env.alerts[0], /لا توجد تقارير مطابقة/);
});

test('invalid date ranges display server validation and do not download', async () => {
    const env = setup({ from_date: '2026-08-01', to_date: '2026-07-01' }, { errors: { to_date: ['تاريخ النهاية يجب أن يكون بعد البداية.'] } }, false);
    const button = { dataset: { exportUrl: '/photo_reports/export-links' } };
    await env.api.downloadPhotos(button);
    assert.equal(env.downloads.length, 0);
    assert.match(env.alerts[0], /تاريخ النهاية/);
    assert.equal(button.disabled, false);
});
