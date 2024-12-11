jQuery(document).ready(function ($) {
    $("#mark-unused-images-button").on("click", function () {
        const button = $(this);
        button.prop("disabled", true).text("Processing...");

        $.ajax({
            url: UIM_Ajax.ajax_url,
            type: "POST",
            dataType: "json",
            data: {
                action: "mark_unused_images",
                security: UIM_Ajax.nonce_mark_unused
            },
            success: function (response) {
                if (response.success) {
                    alert(response.data.message + "\nUsed Images: " + response.data.used_count + "\nUnused Images: " + response.data.unused_count);
                    location.reload();
                } else {
                    alert("Error: " + response.data.message);
                }
                button.prop("disabled", false).text("Mark Unused Images");
            },
            error: function () {
                alert("An error occurred while processing. Please check the console for details.");
                button.prop("disabled", false).text("Mark Unused Images");
            }
        });
    });

    $("#remove-delete-prefix-button").on("click", function () {
        const button = $(this);
        button.prop("disabled", true).text("Processing...");

        $.ajax({
            url: UIM_Ajax.ajax_url,
            type: "POST",
            dataType: "json",
            data: {
                action: "remove_delete_prefix",
                security: UIM_Ajax.nonce_remove_prefix
            },
            success: function (response) {
                if (response.success) {
                    alert(response.data.message + "\nUpdated Images: " + response.data.updated_count);
                    location.reload();
                } else {
                    alert("Error: " + response.data.message);
                }
                button.prop("disabled", false).text("Remove Delete_ Prefix");
            },
            error: function () {
                alert("An error occurred while processing. Please check the console for details.");
                button.prop("disabled", false).text("Remove Delete_ Prefix");
            }
        });
    });
});
