<?php
include("components/header.php");
?>
<div class="d-flex justify-content-between">
    <h5>Hello, <?= $user['FULL_NAME'] ?></h5>
    <button type="button" id="btnAddNewEntry" class="btn btn-primary">New Entry</button>
</div>

<!-- table -->
<div class="container-fluid mt-3 table-container mb-5">
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>LOGIN ID</th>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <?php
            $sql = $db->getMyReports($user['ID']);
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
                    <td><?= $row['LOGIN_ID'] ?></td>
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
                    <td>
                        <?= ($row['TIME_STOP'] < 1) ? "<button class='btnStopDuration btn btn-danger' data-id='" . $row['ID'] . "'>Stop</button>" : '' ?>
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
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- table -->


<!-- New Entry Modal -->
<div class="modal fade" id="newEntryModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content w-75">
            <div class="modal-header">
                <h5 class="modal-title" id="newEntryModalTitle">New Entry</h5>
                <button type="button" id="closeNewEntryModal" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="frmNewEntry">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="newEntryTeam">Group</label>
                        <select id="newEntryTeam" name="newEntryTeam" class="form-control" required>
                            <option value="" disabled selected>Select Group</option>
                            <option value="DPRO - B">DPRO - B</option>
                            <option value="EXPORT & CLOSEOUT">EXPORT & CLOSEOUT</option>
                            <option value="SALES - B & L">SALES - B & L</option>
                            <option value="MARKETING - B">MARKETING - B</option>
                            <option value="CIC">CIC</option>
                            <option value="MARKETING - L">MARKETING - L</option>
                            <option value="ORDER & BUDGET">ORDER & BUDGET</option>
                            <option value="ECRM">ECRM</option>
                            <option value="COMMUNITY MGMT">COMMUNITY MGMT</option>
                            <option value="AU - B">AU - B</option>
                            <option value="CN - B">CN - B</option>
                            <option value="MARKET RESEARCH">MARKET RESEARCH</option>
                            <option value="APAC - B">APAC - B</option>
                            <option value="ANZ - B Prof">ANZ - B Prof</option>
                            <option value="NA - B">NA - B</option>
                            <option value="NZ - B">NZ - B</option>
                            <option value="REVIEW MANAGEMENT - A">REVIEW MANAGEMENT - A</option>
                            <option value="SALONORY">SALONORY</option>
                            <option value="APAC - L">APAC - L</option>
                            <option value="CRM APAC - A">CRM APAC - A</option>
                            <option value="CRM Global - A">CRM Global - A</option>
                            <option value="CRM LA - A">CRM LA - A</option>
                            <option value="CRM NA - A">CRM NA - A</option>
                            <option value="PRICING NA - A">PRICING NA - A</option>
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="newEntryActivity">Activities</label>
                        <select id="newEntryActivity" name="newEntryActivity" class="form-control" required>
                            <option value="" disabled selected>Select Activity</option>
                            <option value="ADMIN SUPPORT">ADMIN SUPPORT</option>
                            <option value="EMAIL READ/WRITE (P)">EMAIL READ/WRITE (P)</option>
                            <option value="INITIATIVES AND PROJECTS">INITIATIVES AND PROJECTS</option>
                            <option value="MEETINGS">MEETINGS</option>
                            <option value="OTHER ACTIVITIES">OTHER ACTIVITIES</option>
                            <option value="REPORTING">REPORTING</option>
                            <option value="TRAININGS">TRAININGS</option>
                            <option value="CLOSEOUT">CLOSEOUT</option>
                            <option value="EXPORT">EXPORT</option>
                            <option value="SALES & MARKETING">SALES & MARKETING</option>
                            <option value="EMAIL SUPPORT">EMAIL SUPPORT</option>
                            <option value="DATA PROCESSING">DATA PROCESSING</option>
                            <option value="PRESENTATION">PRESENTATION</option>
                            <option value="ORDER & BUDGET">ORDER & BUDGET</option>
                            <option value="ECRM">ECRM</option>
                            <option value="PROFESSIONAL">PROFESSIONAL</option>
                            <option value="RETAIL">RETAIL</option>
                            <option value="CHINA BUDGET&ORDERS">CHINA BUDGET&ORDERS</option>
                            <option value="INVOICE PAYMENTS INQUIRY/CONSOLIDATION">INVOICE PAYMENTS INQUIRY/CONSOLIDATION</option>
                            <option value="CONSOLIDATION WITH SUPPLIER">CONSOLIDATION WITH SUPPLIER</option>
                            <option value="TASK">TASK</option>
                            <option value="MARKET RESEARCH REPORTING">MARKET RESEARCH REPORTING</option>
                            <option value="SALONORY">SALONORY</option>
                            <option value="ADMIN SUPPORT - ACE">ADMIN SUPPORT - ACE</option>
                            <option value="PRICING">PRICING</option>
                            <option value="CC ANALYTICS">CC ANALYTICS</option>
                            <option value="DIGI MKTG & ANALYTICS- HAIR RETAIL">DIGI MKTG & ANALYTICS- HAIR RETAIL</option>
                            <option value="DIGI MKTG & ANALYTICS- LHC">DIGI MKTG & ANALYTICS- LHC</option>
                            <option value="DIGI MKTG & ANALYTICS- HCB">DIGI MKTG & ANALYTICS- HCB</option>
                            <option value="SALONORY REVIEW MANAGEMENT">SALONORY REVIEW MANAGEMENT</option>
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="newEntryCategory">Category</label>
                        <select id="newEntryCategory" name="newEntryCategory" class="form-control" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="PROMAX">PROMAX</option>
                            <option value="EMAIL READ/WRITE (P)">EMAIL READ/WRITE (P)</option>
                            <option value="CROSS FUNCTIONAL">CROSS FUNCTIONAL</option>
                            <option value="INTERNAL">INTERNAL</option>
                            <option value="MEETINGS">MEETINGS</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="ADMIN SUPPORT">ADMIN SUPPORT</option>
                            <option value="ADMINISTRATOR">ADMINISTRATOR</option>
                            <option value="ALL OTHERS">ALL OTHERS</option>
                            <option value="BREAK TIME">BREAK TIME</option>
                            <option value="EMAIL READ/WRITE">EMAIL READ/WRITE</option>
                            <option value="EXTRACURRICULAR ACTIVITIES">EXTRACURRICULAR ACTIVITIES</option>
                            <option value="INSTALLATION">INSTALLATION</option>
                            <option value="LUNCH">LUNCH</option>
                            <option value="SHAREPOINT ADMINISTRATION">SHAREPOINT ADMINISTRATION</option>
                            <option value="STRETCH BREAK">STRETCH BREAK</option>
                            <option value="TECHNICAL ISSUES">TECHNICAL ISSUES</option>
                            <option value="3% GPO PAYOUT">3% GPO PAYOUT</option>
                            <option value="ADHOC">ADHOC</option>
                            <option value="AFFLINK PAYOUT">AFFLINK PAYOUT</option>
                            <option value="BROKER SALES REPORT">BROKER SALES REPORT</option>
                            <option value="COMMISSION FEES">COMMISSION FEES</option>
                            <option value="DAILY CREDIT REPORT">DAILY CREDIT REPORT</option>
                            <option value="DAILY SALES REPORT">DAILY SALES REPORT</option>
                            <option value="DASHBOARD REPORT">DASHBOARD REPORT</option>
                            <option value="DEDUCTION AUTHORIZATIONS">DEDUCTION AUTHORIZATIONS</option>
                            <option value="DISPENSER AUTHORIZATIONS">DISPENSER AUTHORIZATIONS</option>
                            <option value="HEAT MAP REPORT">HEAT MAP REPORT</option>
                            <option value="NEW LAUNCHES">NEW LAUNCHES</option>
                            <option value="PALM TREE AUDITS">PALM TREE AUDITS</option>
                            <option value="SALES MONITORING: TO REDE">SALES MONITORING: TO REDE</option>
                            <option value="SCOUT STOCK OVERVIEW">SCOUT STOCK OVERVIEW</option>
                            <option value="SPA">SPA</option>
                            <option value="THE UNITED GROUP">THE UNITED GROUP</option>
                            <option value="TOP 50 ACCOUNTS">TOP 50 ACCOUNTS</option>
                            <option value="TOP 50 PRODUCTS">TOP 50 PRODUCTS</option>
                            <option value="TRAININGS">TRAININGS</option>
                            <option value="TRAININGS (P)">TRAININGS (P)</option>
                            <option value="REPORTING">REPORTING</option>
                            <option value="SALES SUPPORT">SALES SUPPORT</option>
                            <option value="UNIFY">UNIFY</option>
                            <option value="COSTCO WEEKLY REPORTS">COSTCO WEEKLY REPORTS</option>
                            <option value="COUPON TRACKING">COUPON TRACKING</option>
                            <option value="CUT REPORT">CUT REPORT</option>
                            <option value="DG 1010DATA">DG 1010DATA</option>
                            <option value="ECRM/FEATURE VISION">ECRM/FEATURE VISION</option>
                            <option value="GROCERY CHANNEL">GROCERY CHANNEL</option>
                            <option value="INNOVATION TRACKER">INNOVATION TRACKER</option>
                            <option value="MARKET6">MARKET6</option>
                            <option value="POS TRACKER REPORT">POS TRACKER REPORT</option>
                            <option value="PRICE COMPARISON REPORT">PRICE COMPARISON REPORT</option>
                            <option value="PRICING TRACKER">PRICING TRACKER</option>
                            <option value="PROJECT SHINE - DSR">PROJECT SHINE - DSR</option>
                            <option value="SKP REPORT">SKP REPORT</option>
                            <option value="TRADE PROMOTION MANAGEMENT">TRADE PROMOTION MANAGEMENT</option>
                            <option value="SCI">SCI</option>
                            <option value="EMAIL SUPPORT">EMAIL SUPPORT</option>
                            <option value="US CONSUMER FEEDBACK DASHBOARD">US CONSUMER FEEDBACK DASHBOARD</option>
                            <option value="BROKER COMMISSION REPORT">BROKER COMMISSION REPORT</option>
                            <option value="HCB REDEMPTION DATA">HCB REDEMPTION DATA</option>
                            <option value="COMPETITIVE FSI & DIGITAL">COMPETITIVE FSI & DIGITAL</option>
                            <option value="MONTHLY BUSINESS REVIEW">MONTHLY BUSINESS REVIEW</option>
                            <option value="MONTHLY COMPETITIVE REPORT">MONTHLY COMPETITIVE REPORT</option>
                            <option value="SHIPMENTS REPORT">SHIPMENTS REPORT</option>
                            <option value="NEW ITEM TRACKING">NEW ITEM TRACKING</option>
                            <option value="COMPETITIVE REPORT">COMPETITIVE REPORT</option>
                            <option value="IN-STOCK & INVENTORY">IN-STOCK & INVENTORY</option>
                            <option value="SHOPPER SOLUTION - HOME CARE">SHOPPER SOLUTION - HOME CARE</option>
                            <option value="BROKER COMMISSIONS SUMMARY YTD">BROKER COMMISSIONS SUMMARY YTD</option>
                            <option value="CANADA REPORTS">CANADA REPORTS</option>
                            <option value="WALMART - HOME CARE SOLUTIONS">WALMART - HOME CARE SOLUTIONS</option>
                            <option value="PDP">PDP</option>
                            <option value="OPEN DEDUCTIONS AND CHECKBOOK SCORECARD">OPEN DEDUCTIONS AND CHECKBOOK SCORECARD</option>
                            <option value="E-COMMERCE PRICING">E-COMMERCE PRICING</option>
                            <option value="NEW ITEMS PERFORMANCE REPORT">NEW ITEMS PERFORMANCE REPORT</option>
                            <option value="PURCHASE REQUISITION CREATION">PURCHASE REQUISITION CREATION</option>
                            <option value="ORDER AMENDMENT">ORDER AMENDMENT</option>
                            <option value="OTHERS">OTHERS</option>
                            <option value="PROCESS">PROCESS</option>
                            <option value="SALES BI">SALES BI</option>
                            <option value="INNOVIT">INNOVIT</option>
                            <option value="CATALOGUE MONITORING">CATALOGUE MONITORING</option>
                            <option value="AZTEC REPORT">AZTEC REPORT</option>
                            <option value="PRICELIST UPDATE">PRICELIST UPDATE</option>
                            <option value="WOOLWORTHS REPORT">WOOLWORTHS REPORT</option>
                            <option value="IMS REPORTS">IMS REPORTS</option>
                            <option value="MMR REPORT">MMR REPORT</option>
                            <option value="ON INVOICE DEALS">ON INVOICE DEALS</option>
                            <option value="L17 SAP INVOICE DOWNLOAD">L17 SAP INVOICE DOWNLOAD</option>
                            <option value="PROMO ANALYSER">PROMO ANALYSER</option>
                            <option value="TP REPORT">TP REPORT</option>
                            <option value="KMART REPORT">KMART REPORT</option>
                            <option value="MARKETING DASHBOARD">MARKETING DASHBOARD</option>
                            <option value="CHINA BUDGET&ORDERS">CHINA BUDGET&ORDERS</option>
                            <option value="SALES BI REPORT">SALES BI REPORT
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="newEntrySubCategory">Sub Category</label>
                        <select id="newEntrySubCategory" name="newEntrySubCategory" class="form-control" required>
                            <option value="" disabled selected>Select Sub Category</option>
                            <option value="PROMAX">PROMAX</option>
                            <option value="EMAIL READ/WRITE">EMAIL READ/WRITE</option>
                            <option value="OTHERS: NEW PROJECT - UPDATE REMARKS">OTHERS: NEW PROJECT - UPDATE REMARKS</option>
                            <option value="STAKEHOLDER">STAKEHOLDER</option>
                            <option value="FILING OF LEAVES, OT, T&E">FILING OF LEAVES, OT, T&E</option>
                            <option value="KPI AND SLA RELATED">KPI AND SLA RELATED</option>
                            <option value="OT, LEAVE, T&E APPROVAL">OT, LEAVE, T&E APPROVAL</option>
                            <option value="OTHER ADMIN">OTHER ADMIN</option>
                            <option value="OTHER TECHNICAL PROBLEMS">OTHER TECHNICAL PROBLEMS</option>
                            <option value="SALES BI ACCESS REQUESTS">SALES BI ACCESS REQUESTS</option>
                            <option value="PRODLOG REPORTING TOOL">PRODLOG REPORTING TOOL</option>
                            <option value="ALL OTHERS">ALL OTHERS</option>
                            <option value="BREAK TIME">BREAK TIME</option>
                            <option value="5S">5S</option>
                            <option value="AOL">AOL</option>
                            <option value="BCP">BCP</option>
                            <option value="BULLETIN BOARD">BULLETIN BOARD</option>
                            <option value="CI">CI</option>
                            <option value="COMPANY DAY">COMPANY DAY</option>
                            <option value="CSR">CSR</option>
                            <option value="DIVERSITY & INCLUSION">DIVERSITY & INCLUSION</option>
                            <option value="E2E">E2E</option>
                            <option value="HAPSC CUP">HAPSC CUP</option>
                            <option value="HENKEL FAMILY DAY">HENKEL FAMILY DAY</option>
                            <option value="HENKEL HIVE PH">HENKEL HIVE PH</option>
                            <option value="ISO">ISO</option>
                            <option value="M&S MANILA XTEAM COLLABORATION">M&S MANILA XTEAM COLLABORATION</option>
                            <option value="MSU">MSU</option>
                            <option value="NEW HIRE ORIENTATION">NEW HIRE ORIENTATION</option>
                            <option value="OTHERS">OTHERS</option>
                            <option value="SAL&MAT AWARDS">SAL&MAT AWARDS</option>
                            <option value="SHE">SHE</option>
                            <option value="SPORTS & WELLNESS">SPORTS & WELLNESS</option>
                            <option value="SSC CLUBS">SSC CLUBS</option>
                            <option value="SSC COMMUNICATIONS">SSC COMMUNICATIONS</option>
                            <option value="SUSTAINABILITY">SUSTAINABILITY</option>
                            <option value="TEAM BUILDING">TEAM BUILDING</option>
                            <option value="YEAR END">YEAR END</option>
                            <option value="INSTALLATION">INSTALLATION</option>
                            <option value="LUNCH">LUNCH</option>
                            <option value="COACHING">COACHING</option>
                            <option value="GENERAL MEETING">GENERAL MEETING</option>
                            <option value="HUDDLE">HUDDLE</option>
                            <option value="ONE-ON-ONE">ONE-ON-ONE</option>
                            <option value="PMS / EDP">PMS / EDP</option>
                            <option value="TEAM MEETING">TEAM MEETING</option>
                            <option value="SHAREPOINT ADMINISTRATION">SHAREPOINT ADMINISTRATION</option>
                            <option value="STRETCH BREAK">STRETCH BREAK</option>
                            <option value="TECHNICAL ISSUES">TECHNICAL ISSUES</option>
                            <option value="DIAL PRO - 3% GPO PAYOUT REPORT">DIAL PRO - 3% GPO PAYOUT REPORT</option>
                            <option value="ADHOC">ADHOC</option>
                            <option value="DIAL PRO - AFFLINK PAYOUT REPORT">DIAL PRO - AFFLINK PAYOUT REPORT</option>
                            <option value="DIAL PRO - BROKER SALES REPORTS">DIAL PRO - BROKER SALES REPORTS</option>
                            <option value="DIAL PRO -  BROKER COMMISSION REPORTS - DIRECT">DIAL PRO - BROKER COMMISSION REPORTS - DIRECT</option>
                            <option value="DIAL PRO -  BROKER COMMISSION REPORTS - INDIRECT">DIAL PRO - BROKER COMMISSION REPORTS - INDIRECT</option>
                            <option value="DIAL PRO -  DAILY CREDIT REPORT">DIAL PRO - DAILY CREDIT REPORT</option>
                            <option value="DMD DAILY SALES: DAILY RUN">DMD DAILY SALES: DAILY RUN</option>
                            <option value="DIAL PRO - DASHBOARD SALES REPORTS">DIAL PRO - DASHBOARD SALES REPORTS</option>
                            <option value="DIAL PRO -DEDUCTION AUTHORIZATIONS">DIAL PRO -DEDUCTION AUTHORIZATIONS</option>
                            <option value="DIAL PRO -  DISPENSER AUTHORIZATIONS">DIAL PRO - DISPENSER AUTHORIZATIONS</option>
                            <option value="DIAL PRO -  HEATMAP REPORT">DIAL PRO - HEATMAP REPORT</option>
                        </select>
                    </div>

                    <!-- <input type="hidden" id="editUserId" value="">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="editName">Name</label>
                        <input type="text" id="editName" name="editName" class="form-control" required />
                    </div> -->
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End of New Entry Modal -->

<button id="scrollToTop" class="btn btn-dark"><i class="fa-solid fa-arrow-right fa-rotate-270"></i></button>

<?php
include("components/footer.php");
?>
<script>
    $(document).ready(function() {
        $('.nav-home').addClass('active');
        new DataTable('#dataTable');
    });
</script>