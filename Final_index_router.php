<?php
// index_router.php - This script loads in the index.php file.

/* The router accomplishes 3 purposes:
1. It handles post requests to create, update, and delete entries. Then redirects to directory.php
2. It handles get requests to populate sticky form data.
3. It generates blank form data for a new entry. */
 
// Reference the helper functions used in this page.
require('includes/router_functions.php');

/* Validates sticky form data and generates an associative array to be used in the 
html section */

/* Check for submitted input, and either process the data or 
generate the sticky form data array. In order to redirect, this must come 
before the html header. */

// CSC211 Requirement - "Use of a control structure"
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{	
	// Attempt to open the database. The populate function needs the database connection reference to makesafe entries.
	if ($dbc = mysqli_connect('localhost', 'school', 'pettinger', 'registry'))
	{
		// If this a delete action, attempt the delete. The fields don't need to be valid.
		if (!empty($_POST['id']) && is_numeric($_POST['id']) && (!empty($_POST['delete'])))
		{
			delete_entry($dbc, $_POST['id']);
		}
		
		// Attempt to update the database or populate the sticky form if invalid
		// Validate the user input:
		$sticky_form_data = populate_sticky_data(True, $_POST, $dbc);
		if ($sticky_form_data['valid'])
		{
				// If an ID value exists, try to update:
				if (!empty($sticky_form_data['id'])){
					if (!empty($_POST['update']))
					{
						update_entry($sticky_form_data, $dbc);
					}
				} else // If no ID value exists, this is a new entry:
				{
					// Try the sql insert and redirect if successful
					insert_into_entries($sticky_form_data, $dbc);
				}
		}
		mysqli_close($dbc);
	} else // Failed to connect:
	{
		handle_database_error();
	}

// End of post section	
} elseif (!empty($_GET['id']) && is_numeric($_GET['id'])) // This portion is accessed through a link in the registry page only.
{
	// Attempt to populate the sticky form for an edit or delete
	// Attempt to open the database:
	if ($dbc = mysqli_connect('localhost', 'school', 'pettinger', 'registry'))
	{
		// The id value is already verified to be numeric.
		$query = "SELECT * FROM entry WHERE id={$_GET['id']} LIMIT 1";
		if (($results = mysqli_query($dbc, $query)) && (mysqli_num_rows($results) > 0))
		{
			// Get the results and populate the form.
			$rows = mysqli_fetch_array($results);
			$sticky_form_data = populate_sticky_data(False, $rows, $dbc);
		}
	} else // Failed to connect:
	{
		handle_database_error();
	}
	
	// If the collection process failed, generate a blank form data set:
	if (empty($sticky_form_data))
	{
		$sticky_form_data = populate_sticky_data();
	}
	
	mysqli_close($dbc);
	
// End of Get Section
} else
{
	// If no prior data is available for the form, create an empty dataset.
	$sticky_form_data = populate_sticky_data();
}
?>