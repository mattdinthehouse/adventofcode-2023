<?php

$INPUT = file_get_contents('../day01/input.txt'); // same input as day one

// List of digits as strings
// ("zero" isn't included in the brief even though it's a single-digit number)
$DIGITS = [
	'one' => 1,
	'two' => 2,
	'three' => 3,
	'four' => 4,
	'five' => 5,
	'six' => 6,
	'seven' => 7,
	'eight' => 8,
	'nine' => 9,
	1 => 1,
	2 => 2,
	3 => 3,
	4 => 4,
	5 => 5,
	6 => 6,
	7 => 7,
	8 => 8,
	9 => 9,
];

// Explode from a single string to lines
$lines = explode(PHP_EOL, $INPUT);

// Build the list of two-digit numbers
$numbers = [];
foreach($lines as $line) {
	// Extract each matching digit, keyed by its index in the string, accounting for a digit appearing multiple times
	$matches = [];
	foreach($DIGITS as $search => $value) {
		for($i = 0; $i < strlen($line); $i++) {
			$index = strpos($line, $search, $i);

			if($index !== false) {
				$matches[$index] = $value;
				$i = $index;
			}
		}
	}

	// Sort the matching digits by their key (ie - the index it appeared in the string)
	ksort($matches);
	$matches = array_values($matches);

	// Make a two-digit number string out of the first and last digits in the line (which may the same index)
	$first = $matches[0];
	$last = $matches[count($matches) - 1];

	$numbers[] = "{$first}{$last}";
}

// Print the sum
$OUTPUT = array_sum($numbers);

print $OUTPUT;