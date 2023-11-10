$(document).ready(function () {
  const closeSelectShiftModal = () => {
    $("#selectShiftModal").modal("hide");
    $(this).find("input").val("");
  };

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
          $("#selectShiftModal").modal("show");
        } else {
          $(".alert-danger").css("opacity", "1").text("Login Failed!");
          setTimeout(function () {
            $(".alert-danger").css("opacity", "0").text("");
          }, 2000);
        }
      },
    });
  });

  $("#closeSelectShiftModal").click(function (e) {
    e.preventDefault();
    closeSelectShiftModal();
  });

  $("#frmSelectShift").submit(function (e) {
    e.preventDefault();
    var shift = $("#shift").val();
    $.ajax({
      type: "POST",
      url: "backend/endpoints/form-submit.php",
      data: {
        submitType: "SelectShift",
        shift: shift,
      },
      success: function (response) {
        closeSelectShiftModal();
        console.log(response);
        if (response == "200") {
          window.location.href = "users/index.php";
        } else {
          $(".alert-danger").css("opacity", "1").text("Something went wrong!");
          setTimeout(function () {
            $(".alert-danger").css("opacity", "0").text("");
          }, 2000);
        }
      },
    });
  });
});
