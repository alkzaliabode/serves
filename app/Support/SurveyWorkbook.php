<?php

namespace App\Support;

use DOMDocument;
use DOMXPath;
use InvalidArgumentException;
use PharData;

/** Small, text-only XLSX adapter. Files are never extracted or evaluated. */
class SurveyWorkbook
{
    public function write(array $rows, string $sheetName = 'الاستبيانات'): string
    {
        $path = tempnam(sys_get_temp_dir(), 'survey_');
        unlink($path);
        $path .= '.zip';
        $zip = new PharData($path);
        $ns = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
        $zip['[Content_Types].xml'] = '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>';
        $zip['_rels/.rels'] = '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
        $zip['xl/workbook.xml'] = '<workbook xmlns="'.$ns.'" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="'.htmlspecialchars($sheetName, ENT_XML1 | ENT_QUOTES, 'UTF-8').'" sheetId="1" r:id="rId1"/></sheets></workbook>';
        $zip['xl/_rels/workbook.xml.rels'] = '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>';
        $columns = max(1, count($rows[0] ?? []));
        $sheet = '<worksheet xmlns="'.$ns.'"><sheetViews><sheetView workbookViewId="0" rightToLeft="1"><pane ySplit="1" topLeftCell="A2" state="frozen"/></sheetView></sheetViews><cols><col min="1" max="'.$columns.'" width="28" customWidth="1"/></cols><sheetData>';
        foreach ($rows as $i => $row) {
            $sheet .= '<row r="'.($i + 1).'">';
            foreach (array_values($row) as $j => $value) {
                $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', (string) ($value ?? ''));
                $sheet .= '<c r="'.chr(65 + $j).($i + 1).'" t="inlineStr"><is><t xml:space="preserve">'.htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</t></is></c>';
            }
            $sheet .= '</row>';
        }
        $zip['xl/worksheets/sheet1.xml'] = $sheet.'</sheetData></worksheet>';
        unset($zip);

        return $path;
    }

    private function xml(PharData $zip, string $name): DOMXPath
    {
        if (! isset($zip[$name]) || $zip[$name]->getSize() > 20 * 1024 * 1024) {
            throw new InvalidArgumentException('ملف Excel غير صالح أو يتجاوز الحجم المسموح.');
        }
        $content = $zip[$name]->getContent();
        if (stripos($content, '<!DOCTYPE') !== false || stripos($content, '<!ENTITY') !== false) {
            throw new InvalidArgumentException('ملف Excel غير صالح.');
        }
        $doc = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        try {
            if (! $doc->loadXML($content, LIBXML_NONET)) {
                throw new InvalidArgumentException('تعذر قراءة محتوى ملف Excel.');
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        return new DOMXPath($doc);
    }

    public function read(string $path, int $columns = 18): array
    {
        $zip = new PharData($path);
        $workbook = $this->xml($zip, 'xl/workbook.xml');
        $first = $workbook->query('//*[local-name()="sheet"]')->item(0);
        if (! $first) {
            throw new InvalidArgumentException('لا توجد ورقة بيانات.');
        }
        $id = $first->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'id');
        $rels = $this->xml($zip, 'xl/_rels/workbook.xml.rels');
        $target = null;
        foreach ($rels->query('//*[local-name()="Relationship"]') as $rel) {
            if ($rel->getAttribute('Id') === $id && $rel->getAttribute('TargetMode') !== 'External') {
                $target = $rel->getAttribute('Target');
            }
        }
        if (! $target || str_contains($target, '..') || str_contains($target, '\\')) {
            throw new InvalidArgumentException('مسار ورقة البيانات غير صالح.');
        }
        $sheet = $this->xml($zip, str_starts_with($target, '/') ? ltrim($target, '/') : 'xl/'.$target);
        $strings = [];
        if (isset($zip['xl/sharedStrings.xml'])) {
            $shared = $this->xml($zip, 'xl/sharedStrings.xml');
            foreach ($shared->query('//*[local-name()="si"]') as $si) {
                $text = '';
                foreach ($shared->query('.//*[local-name()="t"]', $si) as $t) {
                    $text .= $t->textContent;
                }
                $strings[] = $text;
            }
        }
        $rows = [];
        foreach ($sheet->query('//*[local-name()="sheetData"]/*[local-name()="row"]') as $row) {
            if (count($rows) >= 5001) {
                throw new InvalidArgumentException('الحد الأقصى للاستيراد 5000 صف.');
            }
            $values = array_fill(0, $columns, '');
            foreach ($sheet->query('./*[local-name()="c"]', $row) as $cell) {
                if (! preg_match('/^([A-Z]+)[0-9]+$/', $cell->getAttribute('r'), $match)) {
                    throw new InvalidArgumentException('عنوان خلية غير صالح.');
                }
                $column = 0;
                foreach (str_split($match[1]) as $letter) {
                    $column = $column * 26 + ord($letter) - 64;
                }
                if ($sheet->query('./*[local-name()="f"]', $cell)->length) {
                    throw new InvalidArgumentException('يرجى استبدال المعادلات بقيم نصية في الصف '.$row->getAttribute('r').'.');
                }
                $value = $sheet->evaluate('string(./*[local-name()="v"])', $cell);
                if ($cell->getAttribute('t') === 's') {
                    $value = $strings[(int) $value] ?? '';
                }
                if ($cell->getAttribute('t') === 'inlineStr') {
                    $value = '';
                    foreach ($sheet->query('./*[local-name()="is"]//*[local-name()="t"]', $cell) as $t) {
                        $value .= $t->textContent;
                    }
                }
                if ($column > $columns && trim($value) !== '') {
                    throw new InvalidArgumentException('توجد أعمدة إضافية لا تطابق النموذج.');
                }
                if ($column <= $columns) {
                    $values[$column - 1] = trim($value);
                }
            }
            $rows[(int) $row->getAttribute('r')] = $values;
        }

        return $rows;
    }
}
