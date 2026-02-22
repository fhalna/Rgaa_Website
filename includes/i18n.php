<?php
/**
 * Internationalization helper
 *
 * Provides translation functions for UI strings.
 * Content translations (RGAA criteria, glossary) remain in per-page files.
 */

/**
 * Get a translated string
 *
 * @param string $key Translation key (dot notation)
 * @param string|null $lang Language code (defaults to global $lang)
 * @return string Translated string, or key if not found
 */
function t($key, $lang = null) {
	static $translations = [];

	$lang = $lang ?? ($GLOBALS['lang'] ?? 'fr');

	if (!isset($translations[$lang])) {
		$file = ROOT_DIR . '/lang/' . $lang . '.php';
		if (file_exists($file)) {
			$translations[$lang] = require $file;
		} else {
			$translations[$lang] = [];
		}
	}

	// Support dot notation: "nav.criteria" => $translations['nav']['criteria']
	$keys = explode('.', $key);
	$value = $translations[$lang];
	foreach ($keys as $k) {
		if (is_array($value) && isset($value[$k])) {
			$value = $value[$k];
		} else {
			return $key; // Return the key itself if translation not found
		}
	}

	return is_string($value) ? $value : $key;
}

/**
 * Get the translation status for a key
 *
 * @param string $key Translation key
 * @param string $lang Language code
 * @return string 'validated', 'auto', or 'missing'
 */
function translation_status($key, $lang = 'en') {
	$statusFile = ROOT_DIR . '/lang/' . $lang . '.status.json';
	static $statuses = [];

	if (!isset($statuses[$lang])) {
		if (file_exists($statusFile)) {
			$statuses[$lang] = json_decode(file_get_contents($statusFile), true) ?? [];
		} else {
			$statuses[$lang] = [];
		}
	}

	return $statuses[$lang][$key] ?? 'missing';
}
