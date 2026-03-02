<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Connect to MySQL</title>
</head>

<body>
<?php // Script 12.3 - create_table.php
/* This script connects to the MySQL database */

// Attempt to connect to MySQL and print out messages:
if ($dbc = @mysqli_connect('localhost', 'root', 'pettinger', 'myblog'))
{
	// Define the query:
	$query = 'CREATE TABLE entries (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, title VARCHAR(100) NOT NULL, 
	entry TEXT NOT NULL, date_entered DATETIME NOT NULL) CHARACTER SET utf8';
	
	// Execute the query
	if (@mysqli_query($dbc, $query))
	{
		print '<p>The table has been created!</p>';
	} else
	{
		print '<p style="color: red;">Could not create the table because:<br>' . mysqli_error($dbc) . '</p><p>
		The query being run was: ' . $query . '</p>';
	}
	
	mysqli_close($dbc); // Close the connection
} else
{
	print '<p style="color: red;">Could not connect to the database: <br>' . mysqli_connect_error() . '</p>';
}
?>
</body>
</html>