<?php 
// CSC211 Requirement - "Use of index.php" 
// CSC211 Requirement - "Add new data as index page."

/* The router accomplishes 3 purposes:
1. It handles post requests to create, update, and delete entries. Then redirects to directory.php
2. It handles get requests to populate html safe sticky form data.
3. It generates blank form data for a new entry. 

This must come before the header for the redirects to function. */
require('index_router.php');

// Include the header.
define('TITLE', 'Employee Manager');
// CSC211 Requirement - "Use of the include() directive"
include ('templates/header.html');

print '<h3>Employee Manager</h3><hr>';
print '<form action="" method="POST">';

// Contains functions used across the web app.
require('includes/basic_functions.php');
	
// Show data problems if needed:
if ($sticky_form_data['show_errors'])
{
	print '<ul>';
	foreach ($sticky_form_data['errors'] as $field_error)
	{
		print "<li class=\"red\">$field_error</li>";
	}
	print '</ul>';
}
	
// Format the phone number:
$formatted_phone = format_phone_number($sticky_form_data['phone']);
	
// Generate the sticky form:
// CSC211 Requirement - "Main index page collects data"
// CSC211 Requirement - "Work with concatenating a string"
print "<h4>Full Name:</h4>
<p><input type=\"text\" name=\"first_name\" size=\"36\" placeholder=\"First Name\" value=\"{$sticky_form_data['first_name']}\" required></p>" .
"<p><input type=\"text\" name=\"last_name\" size=\"36\" placeholder=\"Last Name\" value=\"{$sticky_form_data['last_name']}\" required></p>" .
"<hr><h4>Address:</h4>
<p><input type=\"text\" name=\"address\" size=\"36\" placeholder=\"Street Address\" value=\"{$sticky_form_data['address']}\" required></p>" .
"<p><input type=\"text\" name=\"city\" size=\"25\" placeholder=\"City\" value=\"{$sticky_form_data['city']}\" required>" .
" <input type=\"text\" name=\"state\" size=\"5\" placeholder=\"State\" value=\"{$sticky_form_data['state']}\" required></p>" .
"<hr><h4>Contact Information:</h4>
<p><input type=\"tel\" name=\"phone\" size=\"36\" placeholder=\"Phone Number\" maxlength=\"14\" value=\"{$formatted_phone}\" required></p>" .
"<p><input type=\"email\" name=\"email\" size=\"36\" placeholder=\"Email Address\" value=\"{$sticky_form_data['email']}\" required></p>" .
"<hr><h4>Sales Information:</h4>
<p>Sales: $ <input type=\"number\" name=\"sales\" placeholder=\"Sales Value\" value=\"{$sticky_form_data['sales']}\" required></p>";
	
print '<div>';
// Check if this form is updating or creating based on the presence of the id value:
if (empty($sticky_form_data['id']))
{
	print '<input type="submit" name ="submit" value="Create Entry">';
} else
{
	print "<input type=\"hidden\" name=\"id\" value=\"{$sticky_form_data['id']}\">";
	print '<input type="submit" name="update" value="Update Entry">';
	print '<input type="submit" name="delete" value="Delete Entry">';
}
print '</form>';
	
print_button('directory.php', 'Cancel');
print '</div>';
// Include the footer.
include('templates/footer.html');
?>