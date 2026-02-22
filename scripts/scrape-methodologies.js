#!/usr/bin/env node
/**
 * RGAA Test Methodology Scraper
 *
 * Fetches test methodology ("Méthodologie de test") from the official DISIC
 * GitHub repo and injects them into the RGAA 4.1 criteria page.
 *
 * Source: https://github.com/DISIC/accessibilite.numerique.gouv.fr
 * Path:   src/rgaa/criteres/{X.Y}/tests/{Z}.md
 *
 * Usage: node scripts/scrape-methodologies.js
 */

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const INDEX_FILE = path.join(ROOT, 'index.html');
const DATA_DIR = path.join(ROOT, 'data');
const METHODOLOGY_FILE = path.join(DATA_DIR, 'methodologies.json');

const GITHUB_RAW_BASE = 'https://raw.githubusercontent.com/DISIC/accessibilite.numerique.gouv.fr/refs/heads/main/src/rgaa/criteres';

// All 106 RGAA 4.1 criteria
const CRITERIA = [];
for (const [theme, count] of [
	[1, 9], [2, 2], [3, 3], [4, 13], [5, 8], [6, 2], [7, 5],
	[8, 10], [9, 4], [10, 14], [11, 13], [12, 11], [13, 12],
]) {
	for (let i = 1; i <= count; i++) {
		CRITERIA.push(`${theme}.${i}`);
	}
}

/**
 * Fetch a raw file from GitHub using curl
 * Returns null if 404 or error
 */
function curlFetch(url) {
	try {
		const result = execSync(
			`curl -sf --max-time 15 "${url}"`,
			{ encoding: 'utf-8', timeout: 20000 }
		);
		return result;
	} catch {
		return null;
	}
}

/**
 * Parse methodology markdown to extract just the steps (skip YAML front matter)
 */
function parseMethodology(markdown) {
	if (!markdown) return null;

	// Remove YAML front matter
	let body = markdown;
	if (body.startsWith('---')) {
		const endIdx = body.indexOf('---', 3);
		if (endIdx !== -1) {
			body = body.substring(endIdx + 3).trim();
		}
	}

	if (!body) return null;
	return body;
}

/**
 * Convert methodology markdown steps to HTML
 * Handles nested lists (numbered + bullet)
 */
function markdownToHtml(md) {
	if (!md) return '';

	let html = md;

	// Convert markdown bold **text** to <strong>text</strong>
	html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');

	// Convert markdown inline code `code` to <code>code</code>
	html = html.replace(/`([^`]+)`/g, '<code>$1</code>');

	// Convert markdown links [text](url) to text (strip relative links)
	html = html.replace(/\[([^\]]+)\]\([^)]+\)/g, '$1');

	// Split into lines and build HTML structure
	const lines = html.split('\n');
	const result = [];
	const listStack = []; // Track nested list types: 'ol' or 'ul'

	for (let i = 0; i < lines.length; i++) {
		const line = lines[i];
		const trimmed = line.trim();

		if (!trimmed) {
			continue;
		}

		// Determine indentation level (approximate: 3 spaces or a tab = 1 level)
		const leadingSpaces = line.match(/^(\s*)/)[1].length;
		const indentLevel = Math.floor(leadingSpaces / 3);

		// Numbered list item
		const olMatch = trimmed.match(/^(\d+)\.\s+(.+)/);
		if (olMatch) {
			// Close deeper lists
			while (listStack.length > indentLevel + 1) {
				result.push(`</${listStack.pop()}>`);
			}
			// Open new list if needed
			if (listStack.length <= indentLevel || listStack[listStack.length - 1] !== 'ol') {
				if (listStack.length > indentLevel && listStack[listStack.length - 1] === 'ul') {
					result.push(`</${listStack.pop()}>`);
				}
				result.push('<ol>');
				listStack.push('ol');
			}
			result.push(`<li>${olMatch[2]}</li>`);
			continue;
		}

		// Bullet list: "- text" or "  - text"
		const ulMatch = trimmed.match(/^[-*]\s+(.+)/);
		if (ulMatch) {
			// Close deeper lists
			while (listStack.length > indentLevel + 1) {
				result.push(`</${listStack.pop()}>`);
			}
			// Open new list if needed
			if (listStack.length <= indentLevel || listStack[listStack.length - 1] !== 'ul') {
				if (listStack.length > indentLevel && listStack[listStack.length - 1] === 'ol') {
					result.push(`</${listStack.pop()}>`);
				}
				result.push('<ul>');
				listStack.push('ul');
			}
			result.push(`<li>${ulMatch[1]}</li>`);
			continue;
		}

		// Regular text - close all open lists first
		while (listStack.length > 0) {
			result.push(`</${listStack.pop()}>`);
		}
		result.push(`<p>${trimmed}</p>`);
	}

	// Close remaining lists
	while (listStack.length > 0) {
		result.push(`</${listStack.pop()}>`);
	}

	return result.join('\n');
}

/**
 * Main: Fetch all methodologies and inject into index.html
 */
function main() {
	console.log('RGAA 4.1 Test Methodology Scraper\n');

	// Step 1: Fetch all methodologies using curl
	const methodologies = {};
	let totalFetched = 0;
	let totalMissing = 0;

	for (const criterion of CRITERIA) {
		process.stdout.write(`Fetching ${criterion}... `);

		const critMethodologies = [];
		// Probe for test files: try 1.md, 2.md, ... up to 20 (max seen is ~14)
		let consecutiveMisses = 0;
		for (let t = 1; t <= 20; t++) {
			const url = `${GITHUB_RAW_BASE}/${criterion}/tests/${t}.md`;
			const md = curlFetch(url);

			if (!md) {
				consecutiveMisses++;
				if (consecutiveMisses >= 2) break; // Stop after 2 consecutive misses
				continue;
			}
			consecutiveMisses = 0;

			const steps = parseMethodology(md);
			if (steps) {
				critMethodologies.push({
					test: `${criterion}.${t}`,
					testId: `test-${criterion.replace('.', '-')}-${t}`,
					markdown: steps,
					html: markdownToHtml(steps),
				});
				totalFetched++;
			} else {
				totalMissing++;
			}
		}

		methodologies[criterion] = critMethodologies;
		console.log(`${critMethodologies.length} tests`);
	}

	console.log(`\nFetched ${totalFetched} methodologies (${totalMissing} missing)`);

	// Step 2: Save methodologies to JSON
	if (!fs.existsSync(DATA_DIR)) {
		fs.mkdirSync(DATA_DIR, { recursive: true });
	}
	fs.writeFileSync(METHODOLOGY_FILE, JSON.stringify(methodologies, null, 2));
	console.log(`Saved to data/methodologies.json`);

	// Step 3: Inject into index.html
	console.log('\nInjecting methodologies into index.html...');
	let html = fs.readFileSync(INDEX_FILE, 'utf-8');
	let injected = 0;

	for (const [criterion, tests] of Object.entries(methodologies)) {
		for (const test of tests) {
			const testId = test.testId;

			// Find the <li id="test-X-Y-Z"> element
			const liOpenPattern = `id="${testId}"`;
			const liIdx = html.indexOf(liOpenPattern);
			if (liIdx === -1) {
				console.log(`  Warning: ${testId} not found in HTML`);
				continue;
			}

			// Find the closing </li> for this test item
			// Handle nested <li> elements inside the test
			let depth = 0;
			let pos = liIdx;
			// First find the > that closes the opening <li> tag
			while (pos < html.length && html[pos] !== '>') pos++;
			pos++; // skip the >

			// Now count nested li tags to find the matching </li>
			let foundClose = false;
			while (pos < html.length) {
				if (html.substring(pos, pos + 4) === '<li>' || html.substring(pos, pos + 3) === '<li ') {
					depth++;
					pos++;
				} else if (html.substring(pos, pos + 5) === '</li>') {
					if (depth === 0) {
						foundClose = true;
						break;
					}
					depth--;
					pos += 5;
				} else {
					pos++;
				}
			}

			if (!foundClose) {
				console.log(`  Warning: could not find closing </li> for ${testId}`);
				continue;
			}

			// Insert methodology HTML before the </li>
			const indentedHtml = test.html.split('\n').join('\n\t\t\t\t\t\t\t\t\t');
			const methodologyHtml = `\n\t\t\t\t\t\t\t<details class="methodology">\n\t\t\t\t\t\t\t\t<summary>Méthodologie de test</summary>\n\t\t\t\t\t\t\t\t<div class="methodology-content">\n\t\t\t\t\t\t\t\t\t${indentedHtml}\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</details>\n\t\t\t\t\t\t`;

			html = html.substring(0, pos) + methodologyHtml + html.substring(pos);
			injected++;
		}
	}

	// Step 4: Add CSS for methodology blocks in LESS-compatible style
	const methodologyCss = `\n\t\t<!-- Methodology styles -->\n\t\t<style>\n\t\t\t.methodology {\n\t\t\t\tmargin-top: 0.5em;\n\t\t\t\tmargin-bottom: 0.25em;\n\t\t\t\tborder-left: 3px solid #1e517b;\n\t\t\t\tpadding-left: 1em;\n\t\t\t}\n\t\t\t.methodology summary {\n\t\t\t\tcursor: pointer;\n\t\t\t\tcolor: #1e517b;\n\t\t\t\tfont-weight: bold;\n\t\t\t\tfont-size: 0.9em;\n\t\t\t}\n\t\t\t.methodology summary:hover {\n\t\t\t\ttext-decoration: underline;\n\t\t\t}\n\t\t\t.methodology-content {\n\t\t\t\tmargin-top: 0.5em;\n\t\t\t\tfont-size: 0.9em;\n\t\t\t}\n\t\t\t.methodology-content ol,\n\t\t\t.methodology-content ul {\n\t\t\t\tpadding-left: 1.5em;\n\t\t\t}\n\t\t\t.methodology-content li {\n\t\t\t\tmargin-bottom: 0.3em;\n\t\t\t}\n\t\t</style>`;

	// Insert the CSS before </head>
	html = html.replace('</head>', methodologyCss + '\n\t</head>');

	fs.writeFileSync(INDEX_FILE, html);
	console.log(`\nInjected ${injected} methodologies into index.html`);
	console.log('Done!');
}

main();
