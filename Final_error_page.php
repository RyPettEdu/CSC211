<?php
// Show the header, footer, and show a simple error message.
Define("TITLE", "Error");
include ('templates/header.html');
print '<h4 class="red">Something unexpected happened.</h4>';
print '<p>(CSC211: A database error occured.)</p>';
include ('templates/footer.html');
?>