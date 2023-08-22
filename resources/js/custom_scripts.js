window.addEventListener('showModal', event => {           // Add User Modal display
    $("#addUserModal").modal("show");
})
$(document).on('showModalDlt', event => {
    // Show the modal
    $("#showModalDlt").modal('show');

    setTimeout(function() {
        $("#showModalDlt").modal('hide');
    }, 2300); // 1000 seconds = 1000000 milliseconds
});
window.addEventListener('updateModalShow', event => {       // Update Modal display
    $("#updateModal").modal("show");
})
window.addEventListener('closeModal', event => {            // Modal Hide
    $(".modal").modal("hide");
    setTimeout(function(){
    $("span.alert").fadeOut(3000, function(){
    $(this).remove();
        })
    }, 1000); 
})
window.addEventListener('deleteModalShow', event =>{
    $("#deleteModalShow").modal("show");
    })
window.addEventListener('deleteModalHide', event =>{
    $("#deleteModalShow").modal("hide")
})

window.addEventListener('deleteBulkModalShow', event =>{
    $("#deleteBulkModalShow").modal("show");
    })
window.addEventListener('deleteBulkModalHide', event =>{
    $("#deleteBulkModalShow").modal("hide")
})

$(document).ready(function() {
    $("#toggleButton").click(function() {
        $(".checkbox").each(function() {
            $(this).prop("checked", !$(this).prop("checked"));
        });
    });
});
function myFunction($id){
    alert($id);
}
// window.livewire.on('', )
//  deletemodal