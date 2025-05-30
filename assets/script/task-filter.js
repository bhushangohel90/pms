jQuery(document).ready(function($) {
    // Trigger when the task status or user filter is changed
    $('#task_status, #user_filter').on('change', function() {
        var task_status = $('#task_status').val();
        var user_filter = $('#user_filter').val();

        // Perform AJAX request
        $.ajax({
            url: ajaxurl, // Use the ajaxurl global variable provided by WordPress
            type: 'GET',
            data: {
                action: 'filter_tasks',   // Action hook for WordPress to recognize the request
                task_status: task_status,
                user_filter: user_filter
            },
            success: function(response) {
                // Update the task list with the new filtered posts
                $('.author-tasks').html(response);
            }
        });
    });
});
