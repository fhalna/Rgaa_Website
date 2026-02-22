#!/usr/bin/env node
/**
 * Internal link checker for RGAA Website
 *
 * Verifies that internal links in PHP files point to existing files.
 *
 * Usage: node tests/check-links.js
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const ROOT = path.resolve(__dirname, '..');
let passed = 0;
let warnings = 0;
const issues = [];

console.log('Internal Link Checker\n');

// Get all PHP files
const phpFiles = execSync(
	`find ${ROOT} -name "*.php" -not -path "*/node_modules/*" -not -path "*/.git/*" -not -path "*/includes/*" -not -path "*/lang/*" -not -path "*/scripts/*" -not -path "*/tests/*"`,
	{ encoding: 'utf-8' }
).trim().split('\n').filter(Boolean);

// Also check HTML files (error pages)
const htmlFiles = [
	path.join(ROOT, '403.html'),
	path.join(ROOT, '404.html'),
].filter(f => fs.existsSync(f));

const allFiles = [...phpFiles, ...htmlFiles];

for (const file of allFiles) {
	const relPath = path.relative(ROOT, file);
	const content = fs.readFileSync(file, 'utf-8');
	const dir = path.dirname(file);

	// Extract href values (internal links only)
	const hrefRegex = /href="([^"#]*?)"/g;
	let match;
	const checked = new Set();

	while ((match = hrefRegex.exec(content)) !== null) {
		const href = match[1];

		// Skip external links, mailto, tel, javascript
		if (!href || href.startsWith('http') || href.startsWith('mailto:')
			|| href.startsWith('tel:') || href.startsWith('javascript:')
			|| href.startsWith('#') || href.startsWith('data:')) {
			continue;
		}

		if (checked.has(href)) continue;
		checked.add(href);

		// Resolve the path
		let targetPath;
		if (href.startsWith('/')) {
			targetPath = path.join(ROOT, href);
		} else {
			targetPath = path.resolve(dir, href);
		}

		// Check .php extension mapping (old .html links may still be in content)
		const phpEquiv = targetPath.replace(/\.html$/, '.php');

		if (fs.existsSync(targetPath) || fs.existsSync(phpEquiv) || fs.existsSync(targetPath + '/index.php') || fs.existsSync(targetPath + '/index.html')) {
			passed++;
		} else {
			warnings++;
			issues.push({ file: relPath, href, resolved: path.relative(ROOT, targetPath) });
		}
	}
}

console.log(`Checked ${allFiles.length} files`);
console.log(`  Valid links: ${passed}`);
console.log(`  Broken links: ${warnings}`);

if (issues.length > 0) {
	console.log('\nBroken internal links:');
	issues.forEach(i => {
		console.log(`  ${i.file}: href="${i.href}" -> ${i.resolved}`);
	});
}
