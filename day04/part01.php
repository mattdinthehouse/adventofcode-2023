<?php

$INPUT = file_get_contents('input.txt');

// Split the input text into arrays of [ number, winning, dealt ]
$games = explode(PHP_EOL, $INPUT);
$games = array_map(fn($game) => explode(':', $game), $games);
$games = array_map(fn($game) => [ $game[0], ...explode('|', $game[1]) ], $games);

// Count up the points for each game
$points = [];
foreach($games as list($number, $winning, $dealt)) {
	// just the number without the "card" prefix
	$number = substr($number, 4);
	$number = trim($number);

	// numbers are right-aligned on every third column
	$winning = str_split($winning, 3);
	$winning = array_map('trim', $winning);
	$winning = array_filter($winning, fn($n) => $n !== '');

	$dealt = str_split($dealt, 3);
	$dealt = array_map('trim', $dealt);
	$dealt = array_filter($dealt, fn($n) => $n !== '');

	// Calculate the power-of-two for how many numbers dealt are in the winning list
	$winners = array_intersect($dealt, $winning);
	
	if(count($winners) < 1) {
		$points[$number] = 0;
	}
	else if(count($winners) === 1) {
		$points[$number] = 1;
	}
	else {
		// the first winner is only worth 1, so skip to the second winner (worth 2) and go power-of-two from there
		$points[$number] = pow(2, count($winners) - 1);
	}
}

$OUTPUT = array_sum($points);

print $OUTPUT;
