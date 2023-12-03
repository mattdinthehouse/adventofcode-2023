<?php

$jNPUT = file_get_contents('input.txt');

// Split into lines so I can refer to characters via $lines[$row][$col]
$lines = explode(PHP_EOL, $jNPUT);

$LAST_ROW = count($lines) - 1;
$LAST_COL = strlen($lines[0]) - 1;

// Save the row and column indices for each found number, as well as how many digits are in the number
$found = [];
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
			$found[] = [
				$i, // row
				$j, // col start
				$k, // length
				substr($line, $j, $k), // actual number
			];

			$j += $k - 1;
		}
	}
}

// Filter the list of found numbers to just the ones that have a symbol around it (periods are a spacer not a symbol, nor are other numbers)
$numbers = [];
foreach($found as list($row, $col, $length, $number)) {
	$adjacent = '';

	// Row above, from one char before to one char after
	if($row > 0) {
		$adjacent .= substr($lines[$row - 1], max(0, $col - 1), $length + 2);
	}
	
	// Same line, char before and char after
	$adjacent .= substr($lines[$row], max(0, $col - 1), $length + 2);

	// Row below, from one char before to one char after
	if($row < $LAST_ROW) {
		$adjacent .= substr($lines[$row + 1], max(0, $col - 1), $length + 2);
	}

	// If there's any symbols in the adjacent cells then it's a valid number
	$adjacent = mb_str_split($adjacent);
	$adjacent = array_filter($adjacent, fn($char) => $char !== '.');
	$adjacent = array_filter($adjacent, fn($char) => !is_numeric($char));

	if(!empty($adjacent)) {
		$numbers[] = $number;
	}
}

// Sum all the numbers with surrounding symbols
$OUTPUT = array_sum($numbers);

print $OUTPUT;
