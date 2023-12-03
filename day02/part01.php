<?php

$INPUT = file_get_contents('input.txt');

// How many balls of each colour are in the bag
$CONFIG = [
	'red' => 12,
	'green' => 13,
	'blue' => 14,
];

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

	// Work out if the game is valid by comparing the revealed cubes in each set to the configuration
	$valid = true;
	foreach($sets as $set) {
		// Read the set as a CSV of reveals
		$set = str_getcsv($set, ',');
		$set = array_map('trim', $set);

		// Split each reveal into [ count, colour ]
		$set = array_map(fn($reveal) => explode(' ', $reveal, 2), $set);

		foreach($set as list($count, $colour)) {
			if($count > $CONFIG[$colour]) {
				$valid = false;
			}
		}
	}
	
	$games[$number] = $valid;
}

// Sum the IDs of the possible games
$OUTPUT = array_filter($games, fn($game) => $game);
$OUTPUT = array_keys($OUTPUT); // array_filter preserves keys, which are the IDs we want to sum
$OUTPUT = array_sum($OUTPUT);

print $OUTPUT;
