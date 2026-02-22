<?php
/**
 * Script to convert HTML pages to PHP templates.
 *
 * Extracts the main content from each HTML file and wraps it
 * with PHP include-based templates.
 *
 * Usage: php scripts/convert-html-to-php.php
 */

$rootDir = dirname(__DIR__);

// Map of HTML files to their PHP configuration
$pages = [
	// RGAA 4.1 (French)
	'index.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa41',
		'currentPageUrl' => '/index.php',
		'bodyClass' => 'criteres toc-follow',
		'googleVerification' => 'GOOGLE_VERIFICATION_MAIN',
	],
	'rgaa4-1-glossaire.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa41',
		'currentPageUrl' => '/rgaa4-1-glossaire.php',
		'bodyClass' => 'toc-follow',
	],
	'rgaa4-1-base.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa41',
		'currentPageUrl' => '/rgaa4-1-base.php',
		'bodyClass' => 'toc-follow',
	],
	'rgaa4-1-references.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa41',
		'currentPageUrl' => '/rgaa4-1-references.php',
		'bodyClass' => 'toc-follow',
	],
	'rgaa4-1-criteres.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa41',
		'currentPageUrl' => '/index.php',
		'bodyClass' => 'criteres toc-follow',
		'redirect' => '/index.php',
	],

	// RGAA 4.0 (French)
	'rgaa4-criteres.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa4',
		'currentPageUrl' => '/rgaa4-criteres.php',
		'bodyClass' => 'criteres toc-follow',
	],
	'rgaa4-glossaire.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa4',
		'currentPageUrl' => '/rgaa4-glossaire.php',
		'bodyClass' => 'toc-follow',
	],
	'rgaa4-base.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa4',
		'currentPageUrl' => '/rgaa4-base.php',
		'bodyClass' => 'toc-follow',
	],
	'rgaa4-references.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa4',
		'currentPageUrl' => '/rgaa4-references.php',
		'bodyClass' => 'toc-follow',
	],

	// RGAA 3 (French)
	'rgaa3-criteres.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/rgaa3-criteres.php',
		'bodyClass' => 'criteres toc-follow rgaa3',
	],
	'rgaa3-glossaire.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/rgaa3-glossaire.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'rgaa3-cas-particuliers.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/rgaa3-cas-particuliers.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'rgaa3-notes-techniques.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/rgaa3-notes-techniques.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'rgaa3-base.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/rgaa3-base.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'rgaa3-references.html' => [
		'lang' => 'fr',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/rgaa3-references.php',
		'bodyClass' => 'toc-follow rgaa3',
	],

	// Other French pages
	'mentions.html' => [
		'lang' => 'fr',
		'bodyClass' => 'mentions toc-follow',
	],
	'index-ancien.html' => [
		'lang' => 'fr',
		'currentPage' => 'index-ancien',
		'bodyClass' => 'home toc-follow',
	],

	// Error pages (keep as HTML - Apache serves them directly)
	// 403.html and 404.html are not converted

	// English pages
	'en/index.html' => [
		'lang' => 'en',
		'bodyClass' => 'home toc-follow',
	],
	'en/legal-notice.html' => [
		'lang' => 'en',
		'bodyClass' => 'mentions toc-follow',
	],
	'en/rgaa3-criteria.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/en/rgaa3-criteria.php',
		'bodyClass' => 'criteres toc-follow rgaa3',
	],
	'en/rgaa3-glossary.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/en/rgaa3-glossary.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'en/rgaa3-particular-cases.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/en/rgaa3-particular-cases.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'en/rgaa3-technical-notes.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/en/rgaa3-technical-notes.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'en/rgaa3-baseline.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/en/rgaa3-baseline.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'en/rgaa3-references.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa3',
		'currentPageUrl' => '/en/rgaa3-references.php',
		'bodyClass' => 'toc-follow rgaa3',
	],
	'en/rgaa4-criteria.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa4',
		'currentPageUrl' => '/en/rgaa4-criteria.php',
		'bodyClass' => 'criteres toc-follow',
	],
	'en/rgaa4-glossary.html' => [
		'lang' => 'en',
		'rgaaVersion' => 'rgaa4',
		'currentPageUrl' => '/en/rgaa4-glossary.php',
		'bodyClass' => 'toc-follow',
	],
];

foreach ($pages as $htmlFile => $config) {
	$htmlPath = $rootDir . '/' . $htmlFile;
	$phpFile = preg_replace('/\.html$/', '.php', $htmlFile);
	$phpPath = $rootDir . '/' . $phpFile;

	if (!file_exists($htmlPath)) {
		echo "SKIP: $htmlFile (not found)\n";
		continue;
	}

	// Handle redirect files
	if (!empty($config['redirect'])) {
		$redirect = $config['redirect'];
		file_put_contents($phpPath, "<?php header('Location: $redirect', true, 301); exit;\n");
		echo "REDIRECT: $htmlFile -> $phpFile\n";
		continue;
	}

	$html = file_get_contents($htmlPath);

	// Extract title
	preg_match('/<title>(.*?)<\/title>/s', $html, $titleMatch);
	$title = $titleMatch[1] ?? 'RGAA - Tanaguru';

	// Extract meta description
	preg_match('/<meta\s+name="description"\s+content="(.*?)"/s', $html, $descMatch);
	$description = $descMatch[1] ?? '';

	// Extract canonical URL
	preg_match('/rel="canonical"\s+href="https?:\/\/rgaa\.tanaguru\.com(.*?)"/s', $html, $canonMatch);
	$canonicalUrl = $canonMatch[1] ?? '';

	// Extract alternate URLs
	$alternateUrls = [];
	preg_match_all('/rel="alternate"\s+href="https?:\/\/rgaa\.tanaguru\.com(.*?)"\s+hreflang="(.*?)"/s', $html, $altMatches, PREG_SET_ORDER);
	foreach ($altMatches as $match) {
		$alternateUrls[$match[2]] = $match[1];
	}
	// Filter out commented alternates
	$htmlNoComments = preg_replace('/<!--.*?-->/s', '', $html);
	$realAlternates = [];
	preg_match_all('/rel="alternate"\s+href="https?:\/\/rgaa\.tanaguru\.com(.*?)"\s+hreflang="(.*?)"/s', $htmlNoComments, $realAltMatches, PREG_SET_ORDER);
	foreach ($realAltMatches as $match) {
		$realAlternates[$match[2]] = $match[1];
	}

	// Extract google verification
	preg_match('/name="google-site-verification"\s+content="(.*?)"/s', $html, $gvMatch);
	$googleVerification = $gvMatch[1] ?? '';

	// Extract main content: everything between </header> and <footer
	preg_match('/<\/header>\s*(.*?)\s*<footer/s', $html, $contentMatch);
	$mainContent = $contentMatch[1] ?? '';

	// Clean up the content - trim leading tabs
	$mainContent = trim($mainContent);

	// Build the PHP file
	$lang = $config['lang'];
	$includesPath = ($lang === 'en') ? "dirname(__DIR__) . '/includes'" : "__DIR__ . '/includes'";

	$php = "<?php\n";
	$php .= "require_once {$includesPath} . '/config.php';\n\n";

	// Page variables
	$php .= "\$lang = '{$lang}';\n";
	$php .= "\$pageTitle = " . var_export($title, true) . ";\n";
	if ($description) {
		$php .= "\$pageDescription = " . var_export($description, true) . ";\n";
	}
	if ($canonicalUrl) {
		$php .= "\$canonicalUrl = " . var_export($canonicalUrl, true) . ";\n";
	}
	if (!empty($realAlternates)) {
		$php .= "\$alternateUrls = " . var_export($realAlternates, true) . ";\n";
	}
	if ($googleVerification) {
		$php .= "\$googleVerification = " . var_export($googleVerification, true) . ";\n";
	}
	if (!empty($config['rgaaVersion'])) {
		$php .= "\$rgaaVersion = " . var_export($config['rgaaVersion'], true) . ";\n";
	}
	if (!empty($config['currentPageUrl'])) {
		$php .= "\$currentPageUrl = " . var_export($config['currentPageUrl'], true) . ";\n";
	}
	if (!empty($config['currentPage'])) {
		$php .= "\$currentPage = " . var_export($config['currentPage'], true) . ";\n";
	}
	$php .= "\$bodyClass = " . var_export($config['bodyClass'] ?? '', true) . ";\n";

	$php .= "\ninclude {$includesPath} . '/head.php';\n";
	$php .= "include {$includesPath} . '/header.php';\n";
	$php .= "?>\n";

	$php .= $mainContent . "\n";

	$php .= "<?php include {$includesPath} . '/footer.php'; ?>\n";

	file_put_contents($phpPath, $php);
	echo "CONVERTED: $htmlFile -> $phpFile\n";
}

echo "\nDone! All pages converted.\n";
