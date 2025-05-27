<script src="{{asset('/js/jquery-3.7.1.min.js')}}" ></script>
<script>
    $(document).ready(function() {
    // Show the success message when the page loads
    $('#message').show();

    // Set a timer to hide the success message after 5 seconds
    setTimeout(function() {
        $('#message').fadeOut('slow'); // Fade out slowly
    }, 1000); // 1000 milliseconds = 1 seconds
});

</script>