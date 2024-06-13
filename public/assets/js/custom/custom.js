confirmActionByAjax = (url, title, confirmButtonText = "Yes", dataTableName, text = "It can be recovered", callback) => {
    Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": window.Laravel.csrfToken
                },
                success: function (result) {
                    dataTableName.draw();
                    Swal.fire("success!", result.message, "success");
                    if (callback && typeof callback == 'function') {
                        callback();
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.message != "") {
                        Swal.fire("Oh, Snap!", xhr.responseJSON.message, "error");
                    } else {
                        Swal.fire("Oh, Snap!", "Something went wrong!", "error");
                    }
                }
            });
        }
    });
}