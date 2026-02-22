<?php
/**
 * Site configuration
 */

define('SITE_URL', 'https://rgaa.tanaguru.com');
define('SITE_NAME', 'RGAA - Tanaguru');
define('MATOMO_URL', 'https://matomo.tanaguru.com/');
define('MATOMO_SITE_ID', '3');
define('GOOGLE_VERIFICATION_MAIN', 'zQBPqJNd_ExoycP0KGhFEqON9x6qMVEsGFw6sozLobE');
define('GOOGLE_VERIFICATION_ALT', 'pzLp1bE8KeV2q7OeoMR_xpdXJ-mjfKyh00jw3ai75w0');

// Base path for includes
define('INCLUDES_DIR', __DIR__);
define('ROOT_DIR', dirname(__DIR__));

// Load i18n helper
require_once INCLUDES_DIR . '/i18n.php';

/**
 * RGAA version navigation definitions
 */
function get_rgaa_nav($version, $lang = 'fr') {
	$navs = [
		'rgaa41' => [
			'fr' => [
				['url' => '/index.php', 'label' => 'Critères'],
				['url' => '/rgaa4-1-glossaire.php', 'label' => 'Glossaire'],
				['url' => '/rgaa4-1-base.php', 'label' => 'Environnement de test'],
				['url' => '/rgaa4-1-references.php', 'label' => 'Références'],
			],
		],
		'rgaa4' => [
			'fr' => [
				['url' => '/rgaa4-criteres.php', 'label' => 'Critères'],
				['url' => '/rgaa4-glossaire.php', 'label' => 'Glossaire'],
				['url' => '/rgaa4-base.php', 'label' => 'Environnement de test'],
				['url' => '/rgaa4-references.php', 'label' => 'Références'],
			],
			'en' => [
				['url' => '/en/rgaa4-criteria.php', 'label' => 'Criteria'],
				['url' => '/en/rgaa4-glossary.php', 'label' => 'Glossary'],
			],
		],
		'rgaa3' => [
			'fr' => [
				['url' => '/rgaa3-criteres.php', 'label' => 'Critères'],
				['url' => '/rgaa3-glossaire.php', 'label' => 'Glossaire'],
				['url' => '/rgaa3-cas-particuliers.php', 'label' => 'Cas particuliers'],
				['url' => '/rgaa3-notes-techniques.php', 'label' => 'Notes techniques'],
				['url' => '/rgaa3-base.php', 'label' => 'Base de référence'],
				['url' => '/rgaa3-references.php', 'label' => 'Références'],
			],
			'en' => [
				['url' => '/en/rgaa3-criteria.php', 'label' => 'Criteria'],
				['url' => '/en/rgaa3-glossary.php', 'label' => 'Glossary'],
				['url' => '/en/rgaa3-particular-cases.php', 'label' => 'Particular cases'],
				['url' => '/en/rgaa3-technical-notes.php', 'label' => 'Technical notes'],
				['url' => '/en/rgaa3-baseline.php', 'label' => 'Baseline'],
				['url' => '/en/rgaa3-references.php', 'label' => 'References'],
			],
		],
	];

	return $navs[$version][$lang] ?? $navs[$version]['fr'] ?? [];
}
