$(function () {
    $("#response_form").submit(function () {
        // Disable the submit button immediately when the form is submitted
        $("#response_modal input[type=submit]").prop('disabled', true);
    
        $.post(ENQUIRY_RESPONSE_URI.replace("{id}", ENQUIRY_ID), {
            response_category: $("select[name=response_category]").val(),
            responsible_party: $("input[name=responsible_party]").val(),
            message: $("textarea[name=message]").val()
        }, function (data, textStatus, jqXHR) {
            if (jqXHR.status >= 200 && jqXHR.status < 300) {
                $("#response_modal").modal('hide');
                noty({
                    type: 'success',
                    text: jqXHR.status === 201 ? "送信成功！" : "送信成功！"
                });
    
                $("#confirm_download_modal").modal('show');
                $("#confirm_download_modal").on('hide.bs.modal', function (e) {
                    window.location.reload(true);
                });
            } else {
                noty({
                    type: 'error',
                    text: 'Internal Server Error',
                });
            }
            // Re-enable the submit button after the response is received
            $("#response_modal input[type=submit]").prop('disabled', false);
        }).error(function (xhr) {
            try {
                var data = xhr.responseJSON;
                if (_.isObject(data) && data.status === 'error' && data.message) {
                    throw new Error(data.message);
                }
    
                throw new Error('Internal Server Error');
            } catch (error) {
                noty({
                    type: 'error',
                    text: error
                });
            }
            // Re-enable the submit button after an error is caught
            $("#response_modal input[type=submit]").prop('disabled', false);
        });
    
        return false;
    });
    

    $("a.download").click(function () {
        downloadDoc();
        
        // ２．　このボタンを一回押したらこのウインドウは閉じるようにして下さい。
        if($(this).parents("div[role=dialog]").length == 1) {
            $(this).parents("div[role=dialog]").modal("hide");
        }
    });

    function downloadDoc() {
        window.open(ENQUIRY_DOWNLOAD_URI.replace("{id}", ENQUIRY_ID), "_blank");
    }
});