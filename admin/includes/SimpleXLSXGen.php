<?php
/**
 * SimpleXLSXGen - Robust Version
 * Generates proper .xlsx files with defined Dimensions and SharedStrings
 */

class SimpleXLSXGen {
    public $rows = [];
    
    public static function fromArray(array $rows) {
        $xlsx = new self();
        $xlsx->rows = $rows;
        return $xlsx;
    }

    public function downloadAs($filename) {
        $temp = tempnam(sys_get_temp_dir(), 'xlsx');
        $this->saveAs($temp);
        
        if (file_exists($temp) && filesize($temp) > 0) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($temp));
            readfile($temp);
            unlink($temp);
            exit;
        } else {
            echo "Error generating Excel file.";
            exit;
        }
    }

    public function saveAs($filename) {
        $zip = new ZipArchive();
        if ($zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        // --- Data Processing (Shared Strings & Dimensions) ---
        $sharedStrings = [];
        $sharedStringsCount = 0;
        $sheetData = '';
        
        $rowCount = count($this->rows);
        $colCount = 0;
        
        // Use inline strings for simplicity and robustness in this lighter version
        // (Excel 2007+ supports inlineStr perfectly if structure is correct)
        
        foreach ($this->rows as $i => $row) {
            $currentColCount = count($row);
            if ($currentColCount > $colCount) $colCount = $currentColCount;
            
            $sheetData .= '<row r="' . ($i + 1) . '">';
            foreach ($row as $j => $cell) {
                // Column Letter Calculation
                $colLetter = '';
                $n = $j;
                while ($n >= 0) {
                    $colLetter = chr(($n % 26) + 65) . $colLetter;
                    $n = floor($n / 26) - 1;
                }
                
                $ref = $colLetter . ($i + 1);
                
                $val = (string)$cell;
                $isNum = (is_numeric($val) && strlen($val) < 15 && substr($val, 0, 1) != '0');
                
                if ($isNum) {
                     $sheetData .= '<c r="' . $ref . '"><v>' . $val . '</v></c>';
                } else {
                     $sheetData .= '<c r="' . $ref . '" t="inlineStr"><is><t>' . htmlspecialchars($val) . '</t></is></c>';
                }
            }
            $sheetData .= '</row>';
        }

        // Calculate Dimension String (e.g. A1:D5)
        $endColLetter = '';
        $n = $colCount - 1;
        while ($n >= 0) {
            $endColLetter = chr(($n % 26) + 65) . $endColLetter;
            $n = floor($n / 26) - 1;
        }
        $dimension = 'A1:' . $endColLetter . $rowCount;


        // --- XML Files ---

        // [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="xml" ContentType="application/xml"/><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>');

        // _rels/.rels
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');

        // xl/workbook.xml
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets></workbook>');

        // xl/_rels/workbook.xml.rels
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');

        // xl/styles.xml
        $zip->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts><fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills><borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs></styleSheet>');

        // xl/worksheets/sheet1.xml (Correct Dimension)
        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><dimension ref="' . $dimension . '"/><sheetViews><sheetView tabSelected="1" workbookViewId="0"/></sheetViews><sheetFormatPr defaultRowHeight="15"/><sheetData>' . $sheetData . '</sheetData></worksheet>');

        $zip->close();
        return true;
    }
}
?>
