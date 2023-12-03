<?php

$INPUT = file_get_contents('input.txt');

// Explode from a single string to a 2D array of lines and each character in that line
$lines = explode(PHP_EOL, $INPUT);
$lines = array_map('mb_str_split', $lines);

// Build the list of two-digit numbers
$numbers = [];
foreach($lines as $line) {
	// Filter to just the digits, and reset array indexes cos array_filter() preserves them
	$line = array_filter($line, 'is_numeric');
	$line = array_values($line);

	// Make a two-digit number string out of the first and last digits in the line (which may the same index)
	$first = $line[0];
	$last = $line[count($line) - 1];

	$numbers[] = "{$first}{$last}";
}

// Print the sum
$OUTPUT = array_sum($numbers);

print $OUTPUT;