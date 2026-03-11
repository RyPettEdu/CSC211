<?php
include ('templates/header.html');

// CSC211 Requirment - Display files in a directory
// Section is to accomplish the Display files in a directory portion of the assignment.
print '<h3>Employee Documents</h3>' . 
	  '<ul>';
$directory = 'uploads';
$files = scandir($directory);
foreach ($files as $file)
{
	// Check is the path is a file and if the file contains the name of the file.
 	if ((is_file($directory . '/' . $file)) && (substr($file, 0, 1) != '.'))
	{
		print "<li><a href=\"$directory/$file\" download>$file</a></li>";
	}
}
print '</ul>';

print '<hr><h3>Employee Directory</h3>';
/* This script creates a search form and displays the results of the directory search as a table. */
require('includes/basic_functions.php');
print '<p>';
print_button('index.php','Add Employee');

// Change the search placeholder to help visualize if a search filter is active.
if (!empty($_GET['search']))
{
	$search_placeholder = htmlspecialchars($_GET['search']);
} else
{
	$search_placeholder = 'Search First Name';
}

// Prints the search form.
print '<form>' .
	"<input type=\"text\" name=\"search\" placeholder=\"$search_placeholder\" value=\"\">" .
	'<input type="submit" name="submit" value="Search">';
// Adds a clear filter link if the search is active.
if (!empty($_GET['search']))	
{
	print_button('directory.php', 'Clear Filter', True);
}
print '</form>' .
	'</p>' .
	'</div>' .
	'<div class="table_center">';
// This includes the formatted html table result of the SQL search
include('show_directory.php');
print '</div>';
// Include the footer.
include('templates/footer.html');
?>