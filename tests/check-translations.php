<?php
/**
 * Check that English translations cover all French keys
 */
$fr = require dirname(__DIR__) . '/lang/fr.php';
$en = require dirname(__DIR__) . '/lang/en.php';

function flatKeys($arr, $prefix = '') {
	$keys = [];
	foreach ($arr as $k => $v) {
		$key = $prefix ? "$prefix.$k" : $k;
		if (is_array($v)) {
			$keys = array_merge($keys, flatKeys($v, $key));
		} else {
			$keys[] = $key;
		}
	}
	return $keys;
}

$frKeys = flatKeys($fr);
$enKeys = flatKeys($en);
$missing = array_diff($frKeys, $enKeys);

if (count($missing) > 0) {
	echo 'MISSING: ' . implode(', ', $missing);
} else {
	echo 'OK';
}
