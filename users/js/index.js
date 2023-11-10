$(document).ready(function () {
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
});
