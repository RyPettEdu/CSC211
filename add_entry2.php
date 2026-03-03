<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Add a Blog Entry</title>
</head>
<body>
<h1>Add a Blog Entry</h1>
<?php // Script 12.4 - add_entry.php
/* This script adds a blog entry to the database */

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{ // Hanlde the form
	// Connect and select:
	$dbc = mysqli_connect('localhost', 'root', 'pettinger', 'myblog');
		
	// Set the Character Set
	mysqli_set_charset($dbc, 'utf8');
	
	// Validate the form data
	$problem = FALSE;
	if (!empty($_POST['title']) && !empty($_POST['entry']))
	{
		$title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['title'])));
		$entry = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['entry'])));
	} else
	{
		print '<p style="color: red;">Please submit both a title and an entry.</p>';
		$problem = TRUE;
	}
	if (!$problem)
	{
		
		// Define the query
		$query = "INSERT INTO entries (id, title, entry, date_entered) VALUES (0, '$title', '$entry', NOW())";
		
		// Execute the query
		if (@mysqli_query($dbc, $query))
		{
			print '<p>The blog entry has been added!</p>';
		} else
		{
			print '<p style="color: red;">Could not add the entry because:<br>' . mysqli_error($dbc) . 
			'</p><p>The query being run was: ' . $query . '</p>';
		}
	} // No Problem
	
	mysqli_close($dbc); // Close the connection
} // End of form submission if.

// Display the form
?>
<form action="add_entry2.php" method="post">
	<p>Entry Title: <input type="text" name="title" size="40" maxsize="100"></p>
	<p>Entry Text: <textarea name="entry" cols="40" rows="5"></textarea></p>
	<input type="submit" name="submit" value="Post This Entry!">
</form>
</body>
</html>