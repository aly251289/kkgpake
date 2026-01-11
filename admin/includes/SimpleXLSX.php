<?php
/**
 * SimpleXLSX class (Simplified for basic reading)
 * Uses PHP ZipArchive to read .xlsx files (OpenXML)
 */

class SimpleXLSX {
    public $rows = [];
    public $sheets = [];

    public static function parse($filename) {
        $xlsx = new self();
        if ($xlsx->_parse($filename)) {
            return $xlsx;
        }
        return false;
    }

    private function _parse($filename) {
        $zip = new ZipArchive();
        if ($zip->open($filename) !== true) return false;

        // 1. Read Shared Strings (if any)
        $sharedStrings = [];
        if (($xml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
             $dom = new DOMDocument();
             $dom->loadXML($xml);
             foreach ($dom->getElementsByTagName('t') as $t) {
                 $sharedStrings[] = $t->nodeValue;
             }
        }

        // 2. Read Sheet 1
        if (($xml = $zip->getFromName('xl/worksheets/sheet1.xml')) !== false) {
            $dom = new DOMDocument();
            $dom->loadXML($xml);
            $rows = $dom->getElementsByTagName('row');
            
            foreach ($rows as $row) {
                $r = [];
                $cells = $row->getElementsByTagName('c');
                foreach ($cells as $cell) {
                    $val = '';
                    $t = $cell->getAttribute('t'); // type
                    
                    if ($cell->getElementsByTagName('v')->length > 0) {
                        $val = $cell->getElementsByTagName('v')->item(0)->nodeValue;
                    }
                    
                    // If Valid Shared String
                    if ($t == 's' && isset($sharedStrings[$val])) {
                        $val = $sharedStrings[$val];
                    }
                    
                    $r[] = $val;
                }
                $this->rows[] = $r;
            }
        }
        $zip->close();
        return true;
    }
}
?>
