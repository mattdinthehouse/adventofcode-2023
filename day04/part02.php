<?php

$INPUT = file_get_contents('input.txt');

// Split the input text into arrays of [ number, winning, dealt ]
$games = explode(PHP_EOL, $INPUT);
$games = array_map(fn($game) => explode(':', $game), $games);
$games = array_map(fn($game) => [ $game[0], ...explode('|', $game[1]) ], $games);

// Move the games into buckets of the same game number
// (cos we're gonna add copies of the same game into their bucket)
$games = array_map(fn($game) => [ $game ], $games);

// Play each game and count how many time each game number was played
// (for loop instead of foreach cos we're modifying the $games array as we play)
$counts = [];
for($i = 0; $i < count($games); $i++) {
	foreach($games[$i] as list($number, $winning, $dealt)) {
		// just the number without the "card" prefix
		$number = substr($number, 4);
		$number = trim($number);

		$counts[$number] ??= 0;
		$counts[$number]++;

		// numbers are right-aligned on every third column
		$winning = str_split($winning, 3);
		$winning = array_map('trim', $winning);
		$winning = array_filter($winning, fn($n) => $n !== '');

		$dealt = str_split($dealt, 3);
		$dealt = array_map('trim', $dealt);
		$dealt = array_filter($dealt, fn($n) => $n !== '');

		// Calculate how many numbers dealt were in the winners list
		$points = array_intersect($dealt, $winning);
		$points = count($points);

		// Insert the games below this number in-position to this array
		$append = array_slice($games, $i + 1, $points);
		$append = array_column($append, 0); // just one game of each number

		for($j = $i + 1, $k = 0; $k < $points; $j++, $k++) {
			$games[$j][] = $append[$k];
		}
	}
}

$OUTPUT = array_sum($counts);

print $OUTPUT;
