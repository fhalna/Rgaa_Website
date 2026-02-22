#!/usr/bin/env node
/**
 * HTML validation tests for RGAA Website
 *
 * Validates that PHP templates produce valid HTML output.
 * Checks for common issues: missing tags, broken structure, etc.
 *
 * Usage: node tests/validate-html.js
 */

const { execSync } = require('child_process');
const path = require('path');
const fs = require('fs');

const ROOT = path.resolve(__dirname, '..');
let passed = 0;
let failed = 0;
const errors = [];

function test(name, fn) {
	try {
		fn();
		passed++;
		console.log(`  PASS: ${name}`);
	} catch (e) {
		failed++;
		errors.push({ name, error: e.message });
		console.log(`  FAIL: ${name}`);
		console.log(`        ${e.message}`);
	}
}

function assert(condition, message) {
	if (!condition) throw new Error(message);
}

console.log('PHP Template Validation Tests\n');

// Test 1: Check that all PHP files are syntactically valid
console.log('--- PHP Syntax ---');
const phpFiles = execSync(
	`find ${ROOT} -name "*.php" -not -path "*/node_modules/*" -not -path "*/.git/*"`,
	{ encoding: 'utf-8' }
).trim().split('\n').filter(Boolean);

for (const file of phpFiles) {
	const relPath = path.relative(ROOT, file);
	test(`PHP syntax: ${relPath}`, () => {
		const result = execSync(`php -l "${file}" 2>&1`, { encoding: 'utf-8' });
		assert(result.includes('No syntax errors'), `Syntax error in ${relPath}: ${result}`);
	});
}

// Test 2: Check that includes exist
console.log('\n--- Include Files ---');
const requiredIncludes = [
	'includes/config.php',
	'includes/i18n.php',
	'includes/head.php',
	'includes/header.php',
	'includes/footer.php',
	'includes/matomo.php',
];

for (const include of requiredIncludes) {
	test(`Include exists: ${include}`, () => {
		assert(fs.existsSync(path.join(ROOT, include)), `Missing include: ${include}`);
	});
}

// Test 3: Check language files
console.log('\n--- Language Files ---');
const requiredLangs = ['lang/fr.php', 'lang/en.php'];
for (const langFile of requiredLangs) {
	test(`Language file: ${langFile}`, () => {
		assert(fs.existsSync(path.join(ROOT, langFile)), `Missing: ${langFile}`);
		const result = execSync(`php -r "var_export(is_array(require '${path.join(ROOT, langFile)}'));"`, { encoding: 'utf-8' });
		assert(result.trim() === 'true', `${langFile} must return an array`);
	});
}

// Test 4: Check translation completeness
test('Translation completeness: en.php has same keys as fr.php', () => {
	const helperScript = path.join(ROOT, 'tests/check-translations.php');
	const result = execSync(`php "${helperScript}"`, { encoding: 'utf-8' });
	assert(result.trim() === 'OK', `Missing English translations: ${result.trim()}`);
});

// Test 5: Check that no jQuery references remain in JS
console.log('\n--- No jQuery ---');
test('main.js has no jQuery references', () => {
	const mainJs = fs.readFileSync(path.join(ROOT, 'js/main.js'), 'utf-8');
	assert(!mainJs.includes('jQuery'), 'main.js still references jQuery');
	assert(!mainJs.includes('$('), 'main.js still uses $() jQuery calls');
});

test('jQuery library file is removed', () => {
	assert(!fs.existsSync(path.join(ROOT, 'js/jquery-2.1.1.min.js')), 'jquery-2.1.1.min.js should be deleted');
});

// Test 6: Check sitemap
console.log('\n--- Sitemap ---');
test('Sitemap uses HTTPS', () => {
	const sitemap = fs.readFileSync(path.join(ROOT, 'sitemap.xml'), 'utf-8');
	assert(!sitemap.includes('http://rgaa'), 'Sitemap contains http:// URLs (should be https://)');
});

test('Sitemap has no duplicate URLs', () => {
	const sitemap = fs.readFileSync(path.join(ROOT, 'sitemap.xml'), 'utf-8');
	const urls = [...sitemap.matchAll(/<loc>(.*?)<\/loc>/g)].map(m => m[1]);
	const unique = new Set(urls);
	assert(urls.length === unique.size, `Sitemap has ${urls.length - unique.size} duplicate URLs`);
});

// Test 7: Check .htaccess
console.log('\n--- Security ---');
test('.htaccess has CSP header enabled', () => {
	const htaccess = fs.readFileSync(path.join(ROOT, '.htaccess'), 'utf-8');
	assert(htaccess.includes('Content-Security-Policy'), 'CSP header not found in .htaccess');
	assert(!htaccess.includes('#Header set Content-Security-Policy'), 'CSP header is still commented out');
});

test('.htaccess uses HTTPS redirects', () => {
	const htaccess = fs.readFileSync(path.join(ROOT, '.htaccess'), 'utf-8');
	const redirectLines = htaccess.split('\n').filter(l => l.trim().startsWith('Redirect'));
	const httpRedirects = redirectLines.filter(l => l.includes('http://'));
	assert(httpRedirects.length === 0, `${httpRedirects.length} redirects still use http://`);
});

// Test 8: Check no debug variables
console.log('\n--- Code Quality ---');
test('No "toto" debug variable in JS', () => {
	const mainJs = fs.readFileSync(path.join(ROOT, 'js/main.js'), 'utf-8');
	assert(!mainJs.includes('toto'), 'main.js still contains "toto" debug variable');
});

// Summary
console.log(`\n${'='.repeat(40)}`);
console.log(`Results: ${passed} passed, ${failed} failed`);
if (errors.length > 0) {
	console.log('\nFailed tests:');
	errors.forEach(e => console.log(`  - ${e.name}: ${e.error}`));
	process.exit(1);
}
