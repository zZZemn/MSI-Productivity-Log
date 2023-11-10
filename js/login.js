$(document).ready(function () {
  $("#frmLogin").submit(function (e) {
    e.preventDefault();
    var userId = $("#userId").val();
    $.ajax({
      type: "POST",
      url: "backend/endpoints/form-submit.php",
      data: {
        submitType: "Login",
        userId: userId,
      },
      success: function (response) {
        if (response == "admin") {
          window.location.href = "admin/index.php";
        } else if (response == "user") {
          window.location.href = "users/index.php";
        } else {
          $(".alert-danger").css("opacity", "1").text("Login Failed!");
          setTimeout(function () {
            $(".alert-danger").css("opacity", "0").text("");
          }, 2000);
        }
      },
    });
  });
});
