<?php

$INPUT = file_get_contents('input.txt');

// Split input into lines, and pull out the first one which is the seed numbers
$lines = explode(PHP_EOL, $INPUT);

$seeds = array_shift($lines);
$seeds = substr($seeds, 7);
$seeds = str_getcsv($seeds, ' ');

// Read all the maps from the rest of the lines
$maps = [];

foreach($lines as $line) {
	if(!$line) continue;

	if(!is_numeric($line[0])) {
		// Title line
		$map = &$maps[];
		$map['title'] = substr($line, 0, -5); // trim the " map:" off the end
		$map['ranges'] = [];
	}
	else {
		// Ranges line
		list($dst_min, $src_min, $length) = str_getcsv($line, ' ');

		// length is for the number of options in the range, so if min = 98 and length = 2 then range = [ 98, 99 ]
		$dst_max = $dst_min + $length - 1;
		$src_max = $src_min + $length - 1;
		
		$map['ranges'][] = [ $src_min, $src_max, $dst_min, $dst_max ];
	}
}

// Flatten the maps array to a key-value of [ title => ranges ]
$maps = array_column($maps, 'ranges', 'title');

// Route each seed through to the location
// (fortunately the maps are given in-order)
$seeds = array_map(fn($seed) => [ 'seed' => $seed ], $seeds);

foreach($maps as $title => $ranges) {
	list($from, $to) = explode('-to-', $title);

	foreach($seeds as $i => $seed) {
		$src = $seed[$from];
		$dst = $src; // if a number doesn't have a ranged destination, then it's equal to it's src

		foreach($ranges as list($src_min, $src_max, $dst_min, $dst_max)) {
			if($src >= $src_min && $src <= $src_max) {
				// seed value is in range
				$dst = $dst_min + ($src - $src_min);
			}
		}

		$seeds[$i][$to] = $dst;
	}
}

// Find the lowest "location" value
$locations = array_column($seeds, 'location');
sort($locations, SORT_NUMERIC);

$OUTPUT = $locations[0];

print $OUTPUT;
