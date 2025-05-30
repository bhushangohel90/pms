jQuery(document).ready(function($) {
    $('#custom-post-form').submit(function(e) {
        e.preventDefault();  // Prevent default form submission
        
        // Gather form data
        var formData = $(this).serialize();  // Serialize the form data
        
        // Perform AJAX request
        $.ajax({
            url: ticketFormAjax.ajaxurl,  // URL for AJAX handler
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle the response from the server (success or failure)
                if (response.success) {
                    // Display success message with task link
                    var taskUrl = response.data.task_url;  // Extract task URL from response
                    var message = '<p>Task has been successfully added/updated. You can view it <a href="' + taskUrl + '" target="_blank">here</a>.</p>';
                    
                    // Add the message to the task-message container
                    $('#task-message').html(message);

                } else {
                    $('#task-message').html('<p>There was an error while creating/updating the task.</p>');
                }
            },
            error: function() {
                $('#task-message').html('<p>Something went wrong, please try again later.</p>');
            }
        });
    });
});
