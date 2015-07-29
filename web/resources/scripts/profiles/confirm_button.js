   /**
    * Hide the form, and display it only on button click, for
    * confirmation
    * @author Anthony Bocci <boccianthony@yahoo.fr>
    */
    function hideButton(btnId, formId)
    {
        var btnDelete = $("#" + btnId);
        var formDelete = $("#" + formId);
        formDelete.hide();
        btnDelete.click(function() {
            formDelete.show();
            btnDelete.hide();
            setTimeout(function() {
                formDelete.hide();
                btnDelete.show();
            }, 5000);
        });
    }

