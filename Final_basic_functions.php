<?php
// CSC211 Requirement - "Demonstrate the use of a function."
function format_phone_number($number)
{
	// Adds in some display formatting if the number is 10 digits.
	if (!empty($number) && strlen($number) == 10)
	{
		// CSC211 Requirment - "Modify a string"
		$formatted_phone = substr_replace($number, '(', 0, 0);
		$formatted_phone = substr_replace($formatted_phone, ') ', 4, 0);
		$formatted_phone = substr_replace($formatted_phone, '-', 9, 0);
		return $formatted_phone;
	}
	// Returns the input string in the format is not 10 digits
	return $number;
}
function print_button($url, $text, $less_padding=False)
{
	// Creates a simple form to targeting a specific page. Used instead of text links.
	print '<form action="' . $url . '" method="GET"><input type="submit" name="' . $text .'" value="' . $text . '"';
	if ($less_padding) {
		print ' class="form_less_padding" ';
	}
	print '></form>';
}
?>