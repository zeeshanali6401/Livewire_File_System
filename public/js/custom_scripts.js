/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./resources/js/custom_scripts.js ***!
  \****************************************/
window.addEventListener('showModal', function (event) {
  // Add User Modal display
  $("#addUserModal").modal("show");
});
$(document).on('showModalDlt', function (event) {
  // Show the modal
  $("#showModalDlt").modal('show');
  setTimeout(function () {
    $("#showModalDlt").modal('hide');
  }, 2300); // 1000 seconds = 1000000 milliseconds
});

window.addEventListener('updateModalShow', function (event) {
  // Update Modal display
  $("#updateModal").modal("show");
});
window.addEventListener('closeModal', function (event) {
  // Modal Hide
  $(".modal").modal("hide");
  setTimeout(function () {
    $("span.alert").fadeOut(3000, function () {
      $(this).remove();
    });
  }, 1000);
});
window.addEventListener('deleteModalShow', function (event) {
  $("#deleteModalShow").modal("show");
});
window.addEventListener('deleteModalHide', function (event) {
  $("#deleteModalShow").modal("hide");
});
window.addEventListener('deleteBulkModalShow', function (event) {
  $("#deleteBulkModalShow").modal("show");
});
window.addEventListener('deleteBulkModalHide', function (event) {
  $("#deleteBulkModalShow").modal("hide");
});
$(document).ready(function () {
  $("#toggleButton").click(function () {
    $(".checkbox").each(function () {
      $(this).prop("checked", !$(this).prop("checked"));
    });
  });
});
function myFunction($id) {
  alert($id);
}
// window.livewire.on('', )
//  deletemodal
/******/ })()
;