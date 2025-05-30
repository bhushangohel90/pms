<script>
    jQuery(document).ready(function($) {
        // Initialize the user mention functionality
        var mentionList = [];
        
        // Fetch user list for mention
        function getUserList() {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: 'POST',
                data: {
                    action: 'get_user_list' // Custom action to get the users
                },
                success: function(response) {
                    mentionList = response.data;
                }
            });
        }

        // Trigger user mention autocomplete
        $(document).on('keyup', '#post-editor', function(e) {
            var content = $(this).val();
            var mentionText = content.split(' ').pop();

            if (mentionText.startsWith('@')) {
                var suggestions = mentionList.filter(function(user) {
                    return user.user_login.toLowerCase().startsWith(mentionText.substring(1).toLowerCase());
                });

                if (suggestions.length > 0) {
                    showSuggestions(suggestions);
                }
            }
        });

        // Display the suggestion dropdown
        function showSuggestions(suggestions) {
            var $dropdown = $('<ul id="mention-dropdown" style="position:absolute; background-color: #fff; border: 1px solid #ccc; z-index: 9999;"></ul>');

            suggestions.forEach(function(suggestion) {
                $dropdown.append('<li>' + suggestion.user_login + '</li>');
            });

            $('body').append($dropdown);

            // Position dropdown relative to the editor
            $dropdown.css({
                top: $('#post-editor').offset().top + $('#post-editor').height(),
                left: $('#post-editor').offset().left,
                width: $('#post-editor').width(),
            });

            $('#mention-dropdown li').on('click', function() {
                var selectedUser = $(this).text();
                var currentContent = $('#post-editor').val();
                $('#post-editor').val(currentContent + selectedUser + ' ');
                $('#mention-dropdown').remove();
            });
        }

        // Fetch the user list on page load
        getUserList();
    });
</script>