<?php
//require 'vendor/autoload.php';
require '../../vendor/autoload.php';
require_once("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();

$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//$spreadSheet = new \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
//$data = $spreadSheet->getActiveSheet()->toArray();

if (isset($_POST['upload'])) {
    //die("sent");
    if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {

        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
        $filePath =  $_FILES['file']['tmp_name'];
        $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $data = $spreadSheet->getActiveSheet()->toArray();

        // if ('csv' == $extension) {
        //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        // } else {
        //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        //     //var_dump("here");
        // }
        // $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        // $sheetData = $spreadsheet->getActiveSheet()->toArray();

        //var_dump($data);
        $totalRows = 0;
        if ($data) {
            foreach ($data as $row) {

                $totalRows++;

                if ($row['0'] == 'districtcode' || $row['1'] == 'countycode' || $row['2'] == 'subcountycode' || $row['3'] == 'subcountyname') {
                    continue;
                }
                $dbAccess->insert('parishes', [
                    'districtCode' => $row['0'], 'countyCode' => $row['1'], 'subCountyCode' => $row['3'], 'parishCode' => $row['4'],
                    'parishName' => $row['5']
                ]);
            }
        } else {
            die("no data");
        }


        echo "Total rows uploaded are " . $totalRows;
    } else {
        die("some thing went wrong");
    }
} else {
    die("not set");
}
