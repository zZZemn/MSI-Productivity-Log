<?php
include('../../backend/db/db_class.php');
$db = new global_class();

if (isset($_GET['download'])) {
    if ($_GET['download'] == '91asdasdutas6d5as67d5asdtas65d67a5sd65as6d75as67d5a7sd76as5d') {
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
        $fields = array('EMPLOYEE', 'GROUP', 'ACTIVITY', 'SUB CATEGORY', 'STATUS', 'REMARKS', 'START TIME', 'STOP TIME');

        // Display column names as the first row 
        $excelData = implode("\t", array_values($fields)) . "\n";

        // Fetch records from the database 
        $query = $db->getMyReports('');
        if ($query->num_rows > 0) {
            // Output each row of the data 
            while ($row = $query->fetch_assoc()) {
                // You should adjust the keys based on your database columns
                $lineData = array(
                    $row['FULL_NAME'],
                    $row['TEAM'],
                    $row['ACTIVITY'],
                    $row['CATEGORY'],
                    $row['SUB_CATEGORY'],
                    $row['REPORT_STATUS'],
                    $row['REMARKS'],
                    $row['TIME_START'],
                    $row['TIME_STOP']
                );

                array_walk($lineData, 'filterData');
                $excelData .= implode("\t", array_values($lineData)) . "\n";
            }
        } else {
            $excelData .= 'No records found...' . "\n";
        }

        // Headers for download 
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$fileName");

        // Render excel data 
        echo $excelData;
    }
}

// header('Location: ../index.php');
// exit;
