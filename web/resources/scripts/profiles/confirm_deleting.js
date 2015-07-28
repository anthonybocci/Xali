/**
 * Hide the deleting form, and display it only on delete button click, for
 * confirmation
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
$(function() {
    var btnDelete = $("#btn-delete");
    var formDelete = $("#form-delete");
    formDelete.hide();
    btnDelete.click(function() {
        formDelete.show();
        btnDelete.hide();
        setTimeout(function() {
            formDelete.hide();
            btnDelete.show();
        }, 5000);
    });
});

