<?php
include('components/header.php');
?>

<div class="d-flex justify-content-between">
    <h5>Hello, <?= $user['FULL_NAME'] ?></h5>
    <button type="button" id="btnDownLoadFile" class="btn btn-dark">Download</button>
</div>
<!-- table -->
<div class="container mt-3 table-container mb-5">
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Group</th>
                <th>Activity</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Start Time</th>
                <th>Stop Time</th>
                <th>Duration</th>
                <th>Weight Duration</th>
                <th>Time Value Weighted Time</th>
                <th>Utilization %</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <?php
            $sql = $db->getMyReports('');
            $count = 1;
            while ($row = $sql->fetch_array()) {
                $start_time = DateTime::createFromFormat('H:i:s', $row['TIME_START']);
                $stop_time = DateTime::createFromFormat('H:i:s', $row['TIME_STOP']);

                $duration = $start_time->diff($stop_time);

                // TIME VALUE
                $totalSeconds = $duration->s + $duration->i * 60 + $duration->h * 3600;
                $totalSeconds = $duration->s + $duration->i * 60 + $duration->h * 3600;
                $decimalFractionOfDay = $totalSeconds / (24 * 3600);
                $timeValue = number_format($decimalFractionOfDay, 5);

                // UTILIZATION
                $utilizationResult = $timeValue / 7.5;
                $utilization = number_format($utilizationResult, 2);
            ?>
                <tr>

                    <td><?= $count ?></td>
                    <td><?= $row['FULL_NAME'] ?></td>
                    <td><?= $row['TEAM'] ?></td>
                    <td><?= $row['ACTIVITY'] ?></td>
                    <td><?= $row['CATEGORY'] ?></td>
                    <td><?= $row['SUB_CATEGORY'] ?></td>
                    <td><?= $row['REPORT_STATUS'] ?></td>
                    <td><?= $row['REMARKS'] ?></td>
                    <td><?= $start_time->format('h:i a') ?></td>
                    <td><?= ($row['TIME_STOP'] > 1) ? $stop_time->format('h:i a') : '' ?></td>
                    <td><?= ($row['TIME_STOP'] > 1) ? $duration->format('%H:%I:%S') : '' ?></td>
                    <td><?= ($row['TIME_STOP'] > 1) ? $duration->format('%H:%I:%S') : '' ?></td>
                    <td><?= ($row['TIME_STOP'] > 1 && $row['ACTIVITY'] != 'OTHER ACTIVITIES') ? $timeValue : '0.00' ?></td>
                    <td><?= ($row['TIME_STOP'] > 1 && $row['ACTIVITY'] != 'OTHER ACTIVITIES') ? $utilization : '0.00' ?></td>
                </tr>
            <?php
                $count++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Group</th>
                <th>Activity</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Start Time</th>
                <th>Stop Time</th>
                <th>Duration</th>
                <th>Weight Duration</th>
                <th>Time Value Weighted Time</th>
                <th>Utilization %</th>
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