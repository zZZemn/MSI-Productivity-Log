<?php
include('components/header.php');
?>

<div class="d-flex justify-content-between">
    <h5>Hello, <?= $user['FULL_NAME'] ?></h5>
    <button type="button" id="btnDownLoadFile" class="btn btn-dark">Download</button>
</div>

<!-- table -->
<div class="container-fluid mt-3 table-container mb-5">
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Login ID</th>
                <th>Multi Task ID</th>
                <th>Group</th>
                <th>Activity</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Start Time</th>
                <th>Stop Time</th>
                <th>Duration</th>
                <th>Percentage</th>
                <th>Weight Duration</th>
                <th>Time Value Weighted Time</th>
                <th>Utilization %</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <?php
            $sqls = $db->getMyReports('');
            $count = 1;
            while ($rows = $sqls->fetch_array()) {
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


                        echo "<br>Duration Key = " . $durationKey['ID'] . ' | Row ID = ' . $rows['ID'] . ' | USER_ID = ' . $rows['USER_ID'];
                    }

                    echo json_encode($durationArray);
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

                    echo '<br>Start Min:  ' . $minStartTime;
                    echo '<br>Stop Max:  ' . $maxStopTime;

                    $percentageInDecimal = $percentage / 100;

                    $minStartTimeStamp = strtotime($minStartTime);
                    $maxStopTimeStamp = strtotime($maxStopTime);

                    $totalDurationMinMax = $maxStopTimeStamp - $minStartTimeStamp;
                    $totalDurationFormatted = gmdate("H:i:s", $totalDurationMinMax);
                    echo '<br>Total: ' . $totalDurationFormatted;

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
            ?>
                <tr>

                    <td><?= $count ?></td>
                    <td><?= $rows['FULL_NAME'] ?></td>
                    <td><?= $rows['LOGIN_ID'] ?></td>
                    <td><?= $rows['MULTI_TASK_ID'] ?></td>
                    <td><?= $rows['TEAM'] ?></td>
                    <td><?= $rows['ACTIVITY'] ?></td>
                    <td><?= $rows['CATEGORY'] ?></td>
                    <td><?= $rows['SUB_CATEGORY'] ?></td>
                    <td><?= $rows['REPORT_STATUS'] ?></td>
                    <td><?= $rows['REMARKS'] ?></td>
                    <td><?= $start_time->format('h:i a') ?></td>
                    <td><?= ($rows['TIME_STOP'] > 1) ? $stop_time->format('h:i a') : '' ?></td>
                    <td><?= ($rows['TIME_STOP'] > 1) ? $duration->format('%H:%I:%S') : '' ?></td>
                    <td><?= ($rows['TIME_STOP'] > 1) ? $percentage : '' ?></td>
                    <td><?= ($rows['TIME_STOP'] > 1) ? $weightDurationFormatted : '' ?></td>
                    <td><?= ($rows['TIME_STOP'] > 1 && $rows['ACTIVITY'] != 'OTHER ACTIVITIES')
                            ? $timeValue
                            : '0.00'
                        ?>
                    </td>
                    <td><?= ($rows['TIME_STOP'] > 1 && $rows['ACTIVITY'] != 'OTHER ACTIVITIES')
                            ? $utilization
                            : '0.00'
                        ?>
                    </td>
                    <td>
                        <?= ($rows['TIME_STOP'] < 1) ? "<button class='btnStopDuration btn btn-danger' data-id='" . $row['ID'] . "'>Stop</button>" : '' ?>
                    </td>
                </tr>
            <?php
                $count++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Login ID</th>
                <th>Multi Task ID</th>
                <th>Group</th>
                <th>Activity</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Start Time</th>
                <th>Stop Time</th>
                <th>Duration</th>
                <th>Percentage</th>
                <th>Weight Duration</th>
                <th>Time Value Weighted Time</th>
                <th>Utilization %</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- table -->

<button id="scrollToTop" class="btn btn-dark"><i class="fa-solid fa-arrow-right fa-rotate-270"></i></button>

<?php
include('components/footer.php');
?>
<script>
    $(document).ready(function() {
        $('.nav-home').addClass('active');
        new DataTable('#dataTable');
    });
</script>