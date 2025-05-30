jQuery(document).ready(function ($) {
    let isEditing = false;
    let editBugId = null;

    $(".edit-bug").on("click", function () {
        isEditing = true;
        editBugId = $(this).data("bug-id");

        $("#bug_id").val(editBugId);
        $("#page_url").val($(this).data("page-url"));
        $("#screenshot").val($(this).data("screenshot"));
        $("#bug_description").val($(this).data("bug-description"));
        $("#status").val($(this).data("status"));
    });

    $("#bug-report-form").on("submit", function (e) {
        e.preventDefault();

        const formData = {
            action: isEditing ? "edit_bug_report" : "handle_bug_report_ajax", // Correct action names
            nonce: bugReportAjax.nonce,
            post_id: $("input[name='post_id']").val(),
            bug_id: isEditing ? $("#bug_id").val() : "",
            page_url: $("#page_url").val(),
            screenshot: $("#screenshot").val(),
            bug_description: $("#bug_description").val(),
            status: $("#status").val(),
        };

        $.ajax({
            url: bugReportAjax.ajax_url,
            type: "POST",
            data: formData,
            success: function (response) {
                console.log(response); // Debugging
                alert(response.data.message);
                if (response.success) {
                    $("#bug-report-form")[0].reset();
                    isEditing = false;
                    editBugId = null;
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Debugging
                alert("An error occurred. Please try again.");
            },
        });
    });
});