loadSidebar();

function loadSidebar() {
    $.ajax({
        url: 'partials/sidebar.php',
        type: 'GET',
        success: function(data) {
            $('#sidebar').html(data);
            // Rebind event handlers after loading new content
            bindEventHandlers();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            $('#sidebar').html('<p>Failed to load users. Please refresh the page.</p>');
        }
    });
}