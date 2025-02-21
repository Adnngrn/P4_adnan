loadNavbar();

function loadNavbar() {
    $.ajax({
        url: 'partials/navbar.php',
        type: 'GET',
        success: function(data) {
            $('#navbar').html(data);
            // Rebind event handlers after loading new content
            bindEventHandlers();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            $('#navbar').html('<p>Failed to load users. Please refresh the page.</p>');
        }
    });
}
