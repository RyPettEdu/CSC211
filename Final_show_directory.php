<?php
// Director.php
/* This script will print all the entries with edit links, create a link to new entry */
/* This script takes an option search get request to filter the data. */

function handle_database_errors() {
	// ALL DATABASE ERRORS IN DIRECTORY.PHP ROUTE HERE
	header('Location: error_page.php');
	exit();
}

// Attempt to get the existing entries from the database.
if ($dbc = mysqli_connect('localhost', 'school', 'pettinger', 'registry'))
{
	// Generates a search query based on the submitted filter
	if (!empty($_GET['search']))
	{
		$search = mysqli_real_escape_string($dbc, htmlspecialchars($_GET['search']));
		$query = "SELECT * FROM entry WHERE first_name LIKE '%" . $search . "%'";
	} else
	{
		$query = 'SELECT * FROM entry';
	}
	
	if ($results = mysqli_query($dbc, $query))
	{
		// // CSC211 Requirement - "Display contents of database in proper formatting"
		// Print out a table of data:
		print '<table>
		<tr>
		<th>Full Name</th><th>Address</th><th>City</th><th>State</th><th>Phone Number</th><th>Email</th><th>Sales</th><th></th>
		</tr>';
		
		// Calculate the sales total
		$sales_total = 0;
		
		// Print each table row:
		while ($row = mysqli_fetch_array($results))
		{
			// CSC211 Requirement - "Compare 2 data fields using substrings."
			// This is just for the "Compare 2 data field using substrings" Requirement.
			$email = $row['email'];
			if (strtolower(substr($email, 0, 3)) == strtolower(substr($row['first_name'], 0, 3)))
			{
				$email = '*' . $email;
			}
			// Print the table row
			print '<tr>' . 
			"<td>{$row['first_name']} {$row['last_name']}</td>" .
			"<td>{$row['address']}</td>" .
			"<td>{$row['city']}</td>" .
			"<td>{$row['state']}</td>" .
			"<td>" . format_phone_number($row['phone']) . "</td>" .
			"<td>{$email}</td>" .
			"<td>\$" . number_format($row['sales'],2) . "</td>" .
			'<td><form action="index.php" method="GET">' .
			'<input type="hidden" name="id" value="' . $row['id'] . '">' .
			'<input type="submit" name="submit" value="Edit / Delete" >' .
			'</form></td>' .
			'</tr>';
			// CSC211 Requirment - "Perform math using php code."
			$sales_total += $row['sales'];
		}
		// Print the total sales at the bottom and print a user warning if the total sales is filtered.
		if (!empty($_GET['search']))
		{
			$total_type = "Total Filtered Sales";
		} else
		{
			$total_type = "Total Sales";
		}
		Print '<td></td><td></td><td></td><td></td><td></td><td></td><td>$' . 
				number_format($sales_total,2) . "</td><td>$total_type</td>";
		Print '</table>';
	} else
	{
		handle_database_errors();
	}	
	
} else
{
	handle_database_errors();
}
?>