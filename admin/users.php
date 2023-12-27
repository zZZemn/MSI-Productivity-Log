<?php
include('components/header.php');
?>

<div class="d-flex justify-content-between">
    <h5>Hello, <?= $user['FULL_NAME'] ?></h5>
    <button type="button" id="btnOpenFrmAddUser" class="btn btn-primary">Add User</button>
</div>
<!-- table -->
<div class="container mt-3 table-container">
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Login ID</th>
                <th>Name</th>
                <th>Team</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = $db->getUsers();
            if ($sql->num_rows > 0) {
                $count = 1;
                while ($row = $sql->fetch_array()) {
            ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $row['ID'] ?></td>
                        <td><?= $row['FULL_NAME'] ?></td>
                        <td><?= $row['TEAM_ID'] ?></td>
                        <td><?= ($row['STATUS']) ? 'Active' : 'Deactivated' ?></td>
                        <td>
                            <button type="button" class="btn btn-dark btnEditUser btn-action-button" data-id="<?= $row['ID'] ?>" data-name="<?= $row['FULL_NAME'] ?>" data-team="<?= $row['TEAM_ID'] ?>">
                                <i class="fa-regular fa-pen-to-square" style="color: #ffffff;"></i> Edit
                            </button>
                            <button type="button" class="btn <?= ($row['STATUS']) ? 'btn-danger' : 'btn-success' ?> btnChangeUserStatus btn-action-button" data-id="<?= $row['ID'] ?>" data-newstatus="<?= ($row['STATUS']) ? '0' : '1' ?>">
                                <?= ($row['STATUS']) ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </td>
                    </tr>
            <?php
                    $count++;
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Login ID</th>
                <th>Name</th>
                <th>Team</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- table -->

<!-- Add User Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content w-75">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add New Account</h5>
                <button type="button" id="closeAddAccountModal" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="frmAddUser">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="addName">Name</label>
                        <input type="text" id="addName" name="addName" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="addTeam">Team</label>
                        <select id="addTeam" name="addTeam" class="form-control" required>
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
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add User Modal End -->


<!-- Edit User Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content w-75">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Edit Account</h5>
                <button type="button" id="closeEditAccountModal" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="frmEditUser">
                    <input type="hidden" id="editUserId" value="">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="editName">Name</label>
                        <input type="text" id="editName" name="editName" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="editTeam">Team</label>
                        <select id="editTeam" name="editTeam" class="form-control" required>
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
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit User Modal End -->

<?php
include('components/footer.php');
?>
<script>
    $(document).ready(function() {
        $('.nav-users').addClass('active');
        new DataTable('#dataTable');
    });
</script>