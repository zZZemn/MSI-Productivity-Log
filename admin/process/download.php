<?php
include('../../backend/db/db_class.php');
$db = new global_class();

if (isset($_POST['downloadExcelFile'])) {
    // Filter the excel data 
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }

    // Excel file name for download 
    $fileName = "members-data_" . date('Y-m-d') . ".xls";

    // Column names 
    $fields = array('ID', 'EMPLOYEE', 'GROUP', 'ACTIVITY', 'SUB CATEGORY', 'STATUS', 'REMARKS', 'START TIME', 'STOP TIME', 'DURATION', 'WEIGHT DURATION', 'TIME VALUE WEGHTER TIME', 'UTILIZATION');

    // Display column names as first row 
    $excelData = implode("\t", array_values($fields)) . "\n";

    // Fetch records from database 
    $query = $db->getMyReports('');
    if ($query->num_rows > 0) {
        // Output each row of the data 
        while ($row = $query->fetch_assoc()) {
            $lineData = array($row['FULL_NAME'], $row['TEAM'], $row['ACTIVITY'], $row['CATEGORY'], $row['SUB_CATEGORY'], $row['STATUS'], $row['REMARKS'], $row['TIME_START'], $row['TIME_STOP'], 'DURATION', 'WEIGHT DURATION', 'TIME VALUE WEIGHTER TIME', 'UTILIZATION');
            array_walk($lineData, 'filterData');
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }
    } else {
        $excelData .= 'No records found...' . "\n";
    }

    // Headers for download 
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    // Render excel data 
    echo $excelData;

    exit;
}
