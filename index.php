<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSI Productivity Log</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/alert.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-dark">

    <div class="alert alert-danger">
    </div>
    <div class="alert alert-success">
    </div>


    <form id="frmLogin" class="frm-login container card p-5 mt-5">
        <center class="mb-5">
            <h5>Welcome To MSI</h5>
        </center>
        <div class="input-container">
            <input type="text" id="userId" class="form-control" required placeholder="Type your login ID">
        </div>
        <button type="submit" class="btn-login btn btn-primary mt-3">Login</button>
    </form>


    <!-- Add Shift Modal -->
    <div class="modal fade" id="selectShiftModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog d-flex justify-content-center">
            <div class="modal-content w-75">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Select Shift</h5>
                    <button type="button" id="closeSelectShiftModal" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="frmSelectShift">
                        <div class="form-outline mb-4">
                            <select id="shift" class="form-control">
                                <option value="11:00 PM to 08:00 AM">11:00 PM to 08:00 AM</option>
                                <option value="11:00 PM to 08:00 AM">11:00 PM to 08:00 AM</option>
                                <option value="11:00 PM to 08:00 AM">11:00 PM to 08:00 AM</option>
                                <option value="11:00 PM to 08:00 AM">11:00 PM to 08:00 AM</option>
                                <option value="11:00 PM to 08:00 AM">11:00 PM to 08:00 AM</option>
                                <option value="11:00 PM to 08:00 AM">11:00 PM to 08:00 AM</option>
                            </select>
                        </div>
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block">Enter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Add Shift Modal -->

    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/login.js"></script>
</body>

</html>