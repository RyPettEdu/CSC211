<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Manipulate Strings</title>
</head>
<body>
<?php
	const br = '<br>'; // Appended to each echo statement for cleaner output.
	$full_name = "Ryan Pettinger";
	
	// Replaces the given case-insensitive substring with another substring within the full string.
	echo str_ireplace('pettinger', 'P.', $full_name).br; 
	// Prints the remaining section of a string given a case-insensitive substring delineator.
	echo stristr($full_name, ' ').br; 
	// Prints the word count in the string.
	echo str_word_count($full_name).br; 
?>
</body>
</html>