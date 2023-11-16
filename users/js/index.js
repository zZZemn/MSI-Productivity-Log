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

  const closeNewEntryModal = () => {
    $("#newEntryModal").modal("hide");
    $(this).find("select").val("");
  };

  $("#btnAddNewEntry").click(function (e) {
    e.preventDefault();
    $("#newEntryModal").modal("show");
  });

  $("#closeNewEntryModal").click(function (e) {
    e.preventDefault();
    closeNewEntryModal();
  });

  $("#frmNewEntry").submit(function (e) {
    e.preventDefault();
    var team = $("#newEntryTeam").val();
    var activity = $("#newEntryActivity").val();
    var category = $("#newEntryCategory").val();
    var subCategory = $("#newEntryCategory").val();
    console.log(team);
    console.log(activity);
    console.log(category);
    console.log(subCategory);

    $.ajax({
      type: "POST",
      url: "../backend/endpoints/form-submit.php",
      data: {
        submitType: "AddNewEntry",
        team: team,
        activity: activity,
        category: category,
        subCategory: subCategory,
      },
      success: function (response) {
        closeNewEntryModal();
        if (response == "200") {
          alertFunction("alert-success", "New entry added!");
        } else {
          alertFunction("alert-danger", "Something Went Wrong!");
        }
      },
    });
  });

  // Stop Entry
  $(".btnStopDuration").click(function (e) {
    e.preventDefault();
    var id = $(this).data("id");

    $.ajax({
      type: "POST",
      url: "../backend/endpoints/form-submit.php",
      data: {
        submitType: "StopEntry",
        id: id,
      },
      success: function (response) {
        if (response == "200") {
          alertFunction("alert-success", "Task Ended!");
        } else {
          alertFunction("alert-danger", "Something Went Wrong!");
        }
      },
    });
  });
});
