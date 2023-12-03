<?php

$INPUT = file_get_contents('input.txt');

// Break into lines, then each line into a [ number, sets ] game
$lines = explode(PHP_EOL, $INPUT);
$lines = array_map(fn($line) => explode(':', $line, 2), $lines);

// Work out which games are valid (keyed by game number)
$games = [];
foreach($lines as list($number, $sets)) {
	$number = substr($number, 5); // trim "Game " off the beginning so it's just the number

	// Read the sets as a CSV
	$sets = str_getcsv($sets, ';');
	$sets = array_map('trim', $sets);

	// Work the maximum number of cubes of each revealed in each set
	$maximums = [
		'red' => 0,
		'green' => 0,
		'blue' => 0,
	];

	foreach($sets as $set) {
		// Read the set as a CSV of reveals
		$set = str_getcsv($set, ',');
		$set = array_map('trim', $set);

		// Split each reveal into [ count, colour ]
		$set = array_map(fn($reveal) => explode(' ', $reveal, 2), $set);

		foreach($set as list($count, $colour)) {
			$maximums[$colour] = max($maximums[$colour], $count);
		}
	}

	// Save the "power" for each game
	$games[$number] = $maximums['red'] * $maximums['green'] * $maximums['blue'];
}

// Sum the powers of each game
$OUTPUT = array_sum($games);

print $OUTPUT;
