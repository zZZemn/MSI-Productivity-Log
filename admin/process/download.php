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
        $fields = array(
            'EMPLOYEE',
            'GROUP',
            'ACTIVITY',
            'CATEGORY',
            'SUB CATEGORY',
            'STATUS',
            'REMARKS',
            'START TIME',
            'STOP TIME',
            'DURATION',
            'PERCENTAGE',
            'WEIGHT DURATION',
            'TIME VALUE WEIGHTED TIME',
            'UTILIZATION',
        );

        // Display column names as the first row 
        $excelData = implode("\t", array_values($fields)) . "\n";

        // Fetch records from the database 
        $query = $db->getMyReports('');
        if ($query->num_rows > 0) {
            // Output each row of the data 
            while ($rows = $query->fetch_assoc()) {
                $start_time = DateTime::createFromFormat('H:i:s', $rows['TIME_START']);
                $stop_time = DateTime::createFromFormat('H:i:s', $rows['TIME_STOP']);

                $duration = $start_time->diff($stop_time);

                $getMultiTask = $db->checkMultiTaskId($rows['MULTI_TASK_ID']);

                if ($getMultiTask->num_rows > 1 && $rows['MULTI_TASK_ID'] != '') {
                    $percentage = 0;
                    $durationArray = [];
                    while ($multiTaskDuration = $getMultiTask->fetch_array()) {
                        $multiStart_time = DateTime::createFromFormat('H:i:s', $multiTaskDuration['TIME_START']);
                        $multiStop_time = DateTime::createFromFormat('H:i:s', $multiTaskDuration['TIME_STOP']);
                        $multiDuration = $multiStart_time->diff($multiStop_time);

                        $multiDurations = [
                            "ID" => $multiTaskDuration['ID'],
                            "Duration" => $multiDuration->format('%H:%I:%S'),
                            "Start_Time" => $multiTaskDuration['TIME_START'],
                            "Stop_Time" => $multiTaskDuration['TIME_STOP']
                        ];

                        $durationArray[] = $multiDurations;
                    }

                    $durationInSeconds = array_map(function ($item) {
                        list($hours, $minutes, $seconds) = sscanf($item['Duration'], "%d:%d:%d");
                        return $hours * 3600 + $minutes * 60 + $seconds;
                    }, $durationArray);

                    $totalDuration = array_sum($durationInSeconds);

                    foreach ($durationInSeconds as $index => $durations) {
                        $percentageX = ($durations / $totalDuration) * 100;
                        $durationArray[$index]['Percentage'] = $percentageX;
                    }

                    foreach ($durationArray as $durationKey) {
                        if ($durationKey['ID'] == $rows['ID']) {
                            $matchingDuration = $durationKey['Percentage'];
                            $percentage = number_format($durationKey['Percentage'], 2);
                            break;
                        }


                        // echo "<br>Duration Key = " . $durationKey['ID'] . ' | Row ID = ' . $rows['ID'] . ' | USER_ID = ' . $rows['USER_ID'];
                    }

                    // echo json_encode($durationArray);
                    // Weigth Duration
                    $minStartTime = "24:00:00";
                    $maxStopTime = "00:00:00";

                    foreach ($durationArray as $durationKeyOne) {
                        // Compare start times
                        if (strtotime($durationKeyOne['Start_Time']) < strtotime($minStartTime)) {
                            $minStartTime = $durationKeyOne['Start_Time'];
                        }

                        // Compare stop times
                        if (strtotime($durationKeyOne['Stop_Time']) > strtotime($maxStopTime)) {
                            $maxStopTime = $durationKeyOne['Stop_Time'];
                        }
                    }

                    // echo '<br>Start Min:  ' . $minStartTime;
                    // echo '<br>Stop Max:  ' . $maxStopTime;

                    $percentageInDecimal = $percentage / 100;

                    $minStartTimeStamp = strtotime($minStartTime);
                    $maxStopTimeStamp = strtotime($maxStopTime);

                    $totalDurationMinMax = $maxStopTimeStamp - $minStartTimeStamp;
                    $totalDurationFormatted = gmdate("H:i:s", $totalDurationMinMax);
                    // echo '<br>Total: ' . $totalDurationFormatted;

                    $weightDuration =  $percentageInDecimal * $totalDurationMinMax;
                    $weightDurationFormatted = gmdate("H:i:s", $weightDuration);

                    $timeValue = $weightDuration * 24;

                    $utilizationResult = $timeValue / 7.5;
                    $utilization = number_format($utilizationResult, 2);
                } else {
                    $percentage = 100;
                    $percentageInDecimal = $percentage / 100;
                    $durationInSeconds = $duration->s + $duration->i * 60 + $duration->h * 3600;
                    $weightDuration = $percentageInDecimal * $durationInSeconds;
                    $weightDurationFormatted = gmdate("H:i:s", $weightDuration);

                    $timeValue = $weightDuration * 24;

                    $utilizationResult = $timeValue / 7.5;
                    $utilization = number_format($utilizationResult, 2);
                }

                // You should adjust the keys based on your database columns
                $lineData = array(
                    $rows['FULL_NAME'],
                    $rows['TEAM'],
                    $rows['ACTIVITY'],
                    $rows['CATEGORY'],
                    $rows['SUB_CATEGORY'],
                    $rows['REPORT_STATUS'],
                    $rows['REMARKS'],
                    $start_time->format('h:i a'),
                    ($rows['TIME_STOP'] > 1) ? $stop_time->format('h:i a') : '',
                    ($rows['TIME_STOP'] > 1) ? $duration->format('%H:%I:%S') : '',
                    ($rows['TIME_STOP'] > 1) ? $percentage : '',
                    ($rows['TIME_STOP'] > 1) ? $weightDurationFormatted : '',
                    ($rows['TIME_STOP'] > 1 && $rows['ACTIVITY'] != 'OTHER ACTIVITIES')
                        ? $timeValue
                        : '0.00',
                    ($rows['TIME_STOP'] > 1 && $rows['ACTIVITY'] != 'OTHER ACTIVITIES')
                        ? $utilization
                        : '0.00'
                );

                // $fields = array(
                //     'EMPLOYEE',
                //     'GROUP',
                //     'ACTIVITY',
                //     'CATEGORY',
                //     'SUB CATEGORY',
                //     'STATUS',
                //     'REMARKS',
                //     'START TIME',
                //     'STOP TIME',
                //     'DURATION',
                //     'PERCENTAGE',
                //     'WEIGHT DURATION',
                //     'TIME VALUE WEIGHTED TIME',
                //     'UTILIZATION',
                // );

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
