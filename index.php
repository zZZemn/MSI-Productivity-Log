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

    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="js/login.js"></script>
</body>

</html>