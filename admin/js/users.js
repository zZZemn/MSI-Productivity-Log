$(document).ready(function () {
  // scroll to top
  $("#scrollToTop").fadeOut();
  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      $("#scrollToTop").fadeIn();
    } else {
      $("#scrollToTop").fadeOut();
    }
  });

  $("#scrollToTop").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 1);
    return false;
  });

  const alertFunction = (alertType, text) => {
    $("." + alertType)
      .css("opacity", "1")
      .text(text);
    setTimeout(function () {
      $("." + alertType)
        .css("opacity", "0")
        .text("");
      window.location.reload();
    }, 2000);
  };

  // add account modal
  const closeAddModal = () => {
    $("#addAccountModal").modal("hide");
    $(this).find("input").val("");
  };

  $("#btnOpenFrmAddUser").click(function (e) {
    e.preventDefault();
    $("#addAccountModal").modal("show");
  });

  $("#closeAddAccountModal").click(function (e) {
    e.preventDefault();
    closeAddModal();
  });

  $("#frmAddUser").submit(function (e) {
    e.preventDefault();
    var name = $("#addName").val();
    var team = $("#addTeam").val();
    $.ajax({
      type: "POST",
      url: "../backend/endpoints/form-submit.php",
      data: {
        submitType: "AddNewAccount",
        name: name,
        team: team,
      },
      success: function (response) {
        closeAddModal();
        if (response == "200") {
          alertFunction("alert-success", "New user added!");
        } else {
          alertFunction("alert-danger", "Something Went Wrong!");
        }
      },
    });
  });
  //end of add account modal

  //edit account modal
  const closeEditModal = () => {
    $("#editAccountModal").modal("hide");
    $(this).find("input").val("");
  };

  $(".btnEditUser").click(function (e) {
    e.preventDefault();
    var name = $(this).data("name");
    var team = $(this).data("team");
    var userId = $(this).data("id");

    $("#editUserId").val(userId);
    $("#editName").val(name);
    $("#editTeam").val(team);
    $("#editAccountModal").modal("show");
  });

  $("#closeEditAccountModal").click(function (e) {
    e.preventDefault();
    closeEditModal();
  });

  $("#frmEditUser").submit(function (e) {
    e.preventDefault();
    var name = $("#editName").val();
    var team = $("#editTeam").val();
    var id = $("#editUserId").val();
    $.ajax({
      type: "POST",
      url: "../backend/endpoints/form-submit.php",
      data: {
        submitType: "EditAccount",
        id: id,
        name: name,
        team: team,
      },
      success: function (response) {
        closeEditModal();
        if (response == "200") {
          alertFunction("alert-success", "User Edited!");
        } else {
          alertFunction("alert-danger", "Something Went Wrong!");
        }
      },
    });
  });
  //end of edit add account modal

  //   change user status
  $(".btnChangeUserStatus").click(function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    var newStatus = $(this).data("newstatus");

    $.ajax({
      type: "POST",
      url: "../backend/endpoints/form-submit.php",
      data: {
        submitType: "ChangeAccountStatus",
        id: id,
        newStatus: newStatus,
      },
      success: function (response) {
        if (response == "200") {
          alertFunction("alert-success", "User Status Change!");
        } else {
          alertFunction("alert-danger", "Something Went Wrong!");
        }
      },
    });
  });
  //   end of change user status

  // download reports in excel
  $("#btnDownLoadFile").click(function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "process/download.php",
      data: {
        downloadExcelFile: "downloadExcelFile",
      },
      success: function (response) {
        console.log(response);
      },
    });
  });
});
