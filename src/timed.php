<?php
$GLOBALS['timed_slices'] = null;
$GLOBALS['timed_latest'] = null;
function timed($tag = null) {
	$now = microtime(true);
	if($GLOBALS['timed_slices'] === null || $GLOBALS['timed_latest'] === null) {
		$GLOBALS['timed_slices'] = array();
	} else {
		$latest = $GLOBALS['timed_latest'];
		$diff = $now - $latest;
		if(key_exists($tag, $GLOBALS['timed_slices'])) {
			$GLOBALS['timed_slices'][$tag] += $diff;
		} else {
			$GLOBALS['timed_slices'][$tag] = $diff;
		}
	}
	$GLOBALS['timed_latest'] = $now;
}
function timed_print($tag = null, $total = null) {
	if($total == null) {
		$total = timed_total();
		timed(); // Rest to unknown.
	}
	if($tag === null) {
		// Print all.
		printf("Time consumed:\n");
		$tags = array_keys($GLOBALS['timed_slices']);
		sort($tags);
		foreach($tags as $t) {
			timed_print($t, $total);
		}
		printf("Total: %.3f sec.\n", $total);
	} elseif(key_exists($tag, $GLOBALS['timed_slices'])) {
		$time = $GLOBALS['timed_slices'][$tag];
		$procentage = $time / $total;
		timed_print_line($tag, $time, $procentage, "\t");
	} else {
		timed_print_line($tag, 0, 0);
	}
}
function timed_total() {
	return array_sum($GLOBALS['timed_slices']);
}
function timed_print_line($tag, $time, $ratio, $prefix = "") {
	if($tag == null || $tag == '') {
		$tag = 'Unknown';
	}
	printf("%s[%s]: ran %.3f sec. ~ %.1f%%\n", $prefix, $tag, $time, $ratio*100);
}
timed(); // Start the timer.