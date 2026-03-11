<?php
function makesafe_entry($entry, $dbc)
{
	return mysqli_real_escape_string($dbc, strip_tags($entry));
}

function set_validation_error(&$form_data, $reason)
{
	$form_data['valid'] = False;
	// Append the reason to the errors list.
	if (!empty($form_data['errors']))
	{
		array_push($form_data['errors'], $reason);
	} else // Define the array
	{
		$form_data['errors'] = [$reason];
	}
}

function validate_state($user_input)
{
	// Checks if a state code is valid.
	$valid_states = [
		'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL',
		'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT',
		'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI',
		'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
	];
	// Iterate through the list until a match is found
	foreach ($valid_states as $check_state)
	{
		if ($check_state == $user_input)
		{
			return true;
		}
	}
	// No match was found.
	return false;
}

function populate_sticky_data($show_errors=False, &$input_array=NULL, $dbc=NULL)
{
	// CSC211 Requirment - "Demonstrate the use of an array."
	/* Creates a global associative array for the sticky form. If data is available,
	This data will be overwritten */
	$sticky_form_data = [
		'first_name' => '',
		'last_name' => '',
		'address' => '',
		'city' => '',
		'state' => '',
		'phone' => '',
		'email' => '',
		'sales' => '',
		'id' => '',
		// Depending how this page is accessed, report errors to the end user.
		'show_errors' => $show_errors,
		'errors' => [],
		'valid' => True
	];
	// Validates the form data, Lengths are to avoid database limitations.
	if (!empty($input_array) && !empty($dbc))
	{
		// Names
		if (!empty($input_array['first_name']) && strlen($input_array['first_name']) <= 30)
		{
			$sticky_form_data['first_name'] = makesafe_entry($input_array['first_name'], $dbc);
		} else { set_validation_error($sticky_form_data, 'Invalid First Name'); }		
		if (!empty($input_array['last_name']) && strlen($input_array['last_name']) <= 30)
		{
			$sticky_form_data['last_name'] = makesafe_entry($input_array['last_name'], $dbc);
		} else { set_validation_error($sticky_form_data, 'Invalid Last Name'); }
		
		// Address
		if (!empty($input_array['address']) && strlen($input_array['address']) <= 100)
		{
			$sticky_form_data['address'] = makesafe_entry($input_array['address'], $dbc);
		} else { set_validation_error($sticky_form_data, 'Invalid Street Address'); }
		
		if (!empty($input_array['city']) && strlen($input_array['city']) <= 50)
		{
			$sticky_form_data['city'] = makesafe_entry($input_array['city'], $dbc);
		} else { set_validation_error($sticky_form_data, 'Invalid City'); }
		
		if (!empty($input_array['state']) && strlen($input_array['state']) == 2 && validate_state($input_array['state']))
		{
			$sticky_form_data['state'] =  strtoupper(makesafe_entry($input_array['state'], $dbc));
		} else { set_validation_error($sticky_form_data, 'Invalid State'); }
		
		// Contact
		if (!empty($input_array['phone']))
		{
			// Coverts formatted numbers into a 10 digit number
			$phone_number = str_replace('(', '', $input_array['phone']);
			$phone_number = str_replace(')', '', $phone_number);
			$phone_number = str_replace('-', '', $phone_number);
			$phone_number = str_replace(' ', '', $phone_number);
			if (strlen($phone_number) == 10 && is_numeric($phone_number))
			{
				$sticky_form_data['phone'] = $phone_number;
			} else { set_validation_error($sticky_form_data, 'Invalid Phone'); }
		} else { set_validation_error($sticky_form_data, 'Invalid Phone'); }
		
		if (filter_var($input_array['email'], FILTER_VALIDATE_EMAIL)) 
		{
			// CSC211 Requirment - "Correct validation of email formatting."
			$sticky_form_data['email'] = makesafe_entry($input_array['email'], $dbc);
		} else { set_validation_error($sticky_form_data, 'Invalid Email'); }
		
		// sales
		if (is_numeric($input_array['sales']) && $input_array['sales'] >= 0)
		{
			$sticky_form_data['sales'] = $input_array['sales'];
		} else { set_validation_error($sticky_form_data, 'Invalid Sales'); }
		
		// ID
		if (!empty($input_array['id']) && is_numeric($input_array['id']))
		{
			$sticky_form_data['id'] = $input_array['id'];
		} // The ID field is not always required.
	}

	return $sticky_form_data;
}

function insert_into_entries(&$form_data, $dbc)
{
	// CSC211 Requirment - "When data is collected it is saved to the database."
	// Defines and runs the insert query
	$query = "INSERT INTO entry (first_name, last_name, address, city, state, phone, email, sales) VALUES (
		'" . $form_data['first_name'] ."', 
		'" . $form_data['last_name'] ."',
		'" . $form_data['address'] ."',
		'" . $form_data['city'] ."',
		'" . $form_data['state'] ."',
		'" . $form_data['phone'] ."',
		'" . $form_data['email'] ."',"
		. $form_data['sales'] . ")";
		 
	if (mysqli_query($dbc, $query))
	{
		// Insert Successful. Redirect to directory.php
		header('Location: '.'directory.php');
		exit();
		
	} else
	{
		handle_database_error();
	}
}

function delete_entry($dbc, $id)
{
	// CSC211 Requirment - Delete a single record
	$query = "DELETE FROM entry WHERE id=$id LIMIT 1";
	$results = mysqli_query($dbc, $query);
	// Check if the delete worked and redirect to directory.php
	if (mysqli_affected_rows($dbc) == 1)
	{
		// Delete Successful. Redirect to directory.php
		header('Location: '.'directory.php');
		exit();
	} else
	{
		handle_database_error();
	}
}

function update_entry(&$form_data, $dbc)
{
	// CSC211 Requirment - Edit a single record.
	$query = "UPDATE entry SET 
		first_name='" . $form_data['first_name'] ."', 
		last_name='" . $form_data['last_name'] ."',
		address='" . $form_data['address'] ."',
		city='" . $form_data['city'] ."',
		state='" . $form_data['state'] ."',
		phone='" . $form_data['phone'] ."',
		email='" . $form_data['email'] ."',
		sales=" . $form_data['sales'] . " 
		WHERE id=" . $form_data['id'] . 
		' LIMIT 1';
		
	$results = mysqli_query($dbc, $query);
	// Check if the update worked and redirect to directory.php
	if (mysqli_affected_rows($dbc) == 1)
	{
		// Update Successful. Redirect to directory.php
		header('Location: '.'directory.php');
		exit();
	} else
	{
		handle_database_error();
	}
}

function handle_database_error()
{
	// ALL DATABASE FAILURES IN THE INDEX PAGE ROUTE HERE.
	if (!headers_sent()) {
		header('Location: error_page.php');
		exit();
	}
	print '<p class=\"red\">Something unexpected happened.</p>';
	exit();
}