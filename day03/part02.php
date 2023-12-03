<?php

$jNPUT = file_get_contents('input.txt');

// Split into lines so I can refer to characters via $lines[$row][$col]
$lines = explode(PHP_EOL, $jNPUT);

$LAST_ROW = count($lines) - 1;
$LAST_COL = strlen($lines[0]) - 1;

// Save the row and column indices for each found number, as well as how many digits are in the number,
// and do the same for each found gear (an asterisk)
$numbers = [];
$gears = [];
foreach($lines as $i => $line) {
	for($j = 0; $j < strlen($line); $j++) {
		$char = $line[$j];

		if(is_numeric($char)) {
			// We have a digit, so proceed until we get the end of the overall number
			// (starting with 1 because we already know $line[$j] is a number)
			for($k = 1; $j + $k < strlen($line); $k++) {
				$char = $line[$j + $k];

				if(!is_numeric($char)) {
					break;
				}
			}

			// Got to the end of the digits, so save the number and advance $j to the end of it
			$numbers[] = [
				$i, // row
				$j, // col start
				$k, // length
				substr($line, $j, $k), // actual number
			];

			$j += $k - 1;
		}
		else if($char === '*') {
			// We have an asterisk so save the coors
			$gears[] = [
				$i, // row
				$j, // col
			];
		}
	}
}

// Filter the list of found gears to just the ones that have two numbers in adjacent cells,
// then calculate and save the "gear ratio" (the two numbers multiplied)
$ratios = [];
foreach($gears as list($y, $x)) {
	// Find any adjacent numbers using AABB
	$adjacent = [];
	foreach($numbers as list($top, $left, $width, $number)) {
		$horizontal = ($x >= $left - 1 && $x <= $left + $width);
		$vertical = ($y >= $top - 1 && $y <= $top + 1);

		if($horizontal && $vertical) {
			$adjacent[] = $number;
		}
	}

	if(count($adjacent) === 2) {
		$ratios[] = $adjacent[0] * $adjacent[1];
	}
}

// Sum all the gear ratios
$OUTPUT = array_sum($ratios);

print $OUTPUT;
