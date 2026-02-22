#!/usr/bin/env node
/**
 * RGAA Data Extractor
 *
 * Parses RGAA HTML criteria pages and extracts structured JSON data
 * for use by the MCP server and other tools.
 *
 * Usage: node scripts/extract-rgaa-data.js
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const DATA_DIR = path.join(ROOT, 'data');

/**
 * Extract criteria from an RGAA criteria HTML file
 */
function extractCriteria(htmlFile, version) {
	const html = fs.readFileSync(htmlFile, 'utf-8');
	const criteria = [];

	// Split HTML by article boundaries to get theme blocks
	const articleParts = html.split(/<article[^>]*class="thematique"[^>]*>/);
	articleParts.shift(); // Remove content before first article

	for (const articleHtml of articleParts) {
		const themeContent = articleHtml.split('</article>')[0] || articleHtml;

		// Extract theme title from the h2 inside header
		const themeTitleMatch = themeContent.match(/<h2[^>]*>([\s\S]*?)<\/h2>/);
		if (!themeTitleMatch) continue;
		const themeTitle = themeTitleMatch[1].replace(/<[^>]+>/g, '').trim();

		// Get theme ID from the article's preceding id attribute
		const themeIdMatch = html.match(new RegExp('<article[^>]*id="([^"]*)"[^>]*class="thematique"'));
		// Better: find the h2 id
		const h2IdMatch = themeContent.match(/<h2[^>]*id="([^"]*)"[^>]*>/);
		const themeId = h2IdMatch ? h2IdMatch[1] : '';

		// Split by <section data-level="..."> to get individual criteria
		const sectionParts = themeContent.split(/<section\s+data-level="([^"]*)"[^>]*>/);

		// sectionParts: [before, level1, content1, level2, content2, ...]
		for (let i = 1; i < sectionParts.length; i += 2) {
			const level = sectionParts[i];
			const critContent = (sectionParts[i + 1] || '').split('</section>')[0];

			// Extract criterion title and number
			const critTitleMatch = critContent.match(/<h3[^>]*id="([^"]*)"[^>]*>([\s\S]*?)<\/h3>/);
			if (!critTitleMatch) continue;

			const critId = critTitleMatch[1];
			const critTitleHtml = critTitleMatch[2];
			const critTitle = critTitleHtml.replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();

			// Extract criterion number
			const numMatch = critId.match(/crit-(\d+)-(\d+)/);
			const criterionNumber = numMatch ? `${numMatch[1]}.${numMatch[2]}` : critId;

			// Extract tests: look for li elements with test IDs
			const tests = [];
			const testRegex = /<li[^>]*id="(test-[\d-]+)"[^>]*>/g;
			let testMatch;
			const testPositions = [];

			while ((testMatch = testRegex.exec(critContent)) !== null) {
				testPositions.push({ id: testMatch[1], start: testMatch.index + testMatch[0].length });
			}

			for (let t = 0; t < testPositions.length; t++) {
				const testId = testPositions[t].id;
				const start = testPositions[t].start;
				// Get text until the next test or end
				const end = t + 1 < testPositions.length ? testPositions[t + 1].start - 50 : critContent.length;
				const rawText = critContent.substring(start, Math.min(start + 600, end));
				const testText = rawText.replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();

				const testNumMatch = testId.match(/test-([\d-]+)/);
				const testNumber = testNumMatch ? testNumMatch[1].replace(/-/g, '.') : testId;

				tests.push({
					id: testId,
					number: testNumber,
					description: testText.substring(0, 500),
				});
			}

			criteria.push({
				id: critId,
				number: criterionNumber,
				title: critTitle,
				level: level,
				theme: themeTitle,
				themeId: themeId,
				tests: tests,
			});
		}
	}

	return criteria;
}

/**
 * Extract glossary terms from an RGAA glossary HTML file
 */
function extractGlossary(htmlFile) {
	const html = fs.readFileSync(htmlFile, 'utf-8');
	const terms = [];

	// Match glossary sections
	const sectionRegex = /<section[^>]*class="section-glossary"[^>]*>([\s\S]*?)<\/section>/g;
	let match;

	while ((match = sectionRegex.exec(html)) !== null) {
		const content = match[1];

		const titleMatch = content.match(/<h3[^>]*id="([^"]*)"[^>]*>([\s\S]*?)<\/h3>/);
		if (!titleMatch) continue;

		const termId = titleMatch[1];
		const termTitle = titleMatch[2].replace(/<[^>]+>/g, '').trim();

		// Extract definition (everything after h3, simplified)
		const defStart = content.indexOf('</h3>');
		if (defStart === -1) continue;
		const definition = content.substring(defStart + 5)
			.replace(/<[^>]+>/g, ' ')
			.replace(/\s+/g, ' ')
			.trim()
			.substring(0, 1000); // Truncate long definitions

		terms.push({
			id: termId,
			term: termTitle,
			definition: definition,
		});
	}

	return terms;
}

// Extract data for each version
const versions = [
	{
		id: 'rgaa41',
		label: 'RGAA 4.1',
		criteriaFile: 'index.html',
		glossaryFile: 'rgaa4-1-glossaire.html',
	},
	{
		id: 'rgaa4',
		label: 'RGAA 4.0',
		criteriaFile: 'rgaa4-criteres.html',
		glossaryFile: 'rgaa4-glossaire.html',
	},
	{
		id: 'rgaa3',
		label: 'RGAA 3.2017',
		criteriaFile: 'rgaa3-criteres.html',
		glossaryFile: 'rgaa3-glossaire.html',
	},
];

if (!fs.existsSync(DATA_DIR)) {
	fs.mkdirSync(DATA_DIR, { recursive: true });
}

const allData = {
	metadata: {
		source: 'https://github.com/Tanaguru/Rgaa_Website',
		generatedAt: new Date().toISOString(),
		versions: versions.map(v => ({ id: v.id, label: v.label })),
	},
	versions: {},
};

for (const version of versions) {
	console.log(`Extracting ${version.label}...`);

	const criteriaFile = path.join(ROOT, version.criteriaFile);
	const glossaryFile = path.join(ROOT, version.glossaryFile);

	const criteria = fs.existsSync(criteriaFile)
		? extractCriteria(criteriaFile, version.id)
		: [];

	const glossary = fs.existsSync(glossaryFile)
		? extractGlossary(glossaryFile)
		: [];

	const themes = [...new Set(criteria.map(c => c.theme))];

	allData.versions[version.id] = {
		label: version.label,
		criteria,
		glossary,
		themes,
		stats: {
			totalCriteria: criteria.length,
			totalTests: criteria.reduce((sum, c) => sum + c.tests.length, 0),
			totalGlossaryTerms: glossary.length,
			byLevel: {
				A: criteria.filter(c => c.level === 'A').length,
				AA: criteria.filter(c => c.level === 'AA').length,
				AAA: criteria.filter(c => c.level === 'AAA').length,
			},
		},
	};

	// Also write per-version files
	const versionFile = path.join(DATA_DIR, `${version.id}.json`);
	fs.writeFileSync(versionFile, JSON.stringify(allData.versions[version.id], null, 2));
	console.log(`  -> ${criteria.length} criteria, ${glossary.length} glossary terms`);
	console.log(`  -> Saved to data/${version.id}.json`);
}

// Write combined file
const combinedFile = path.join(DATA_DIR, 'rgaa-all.json');
fs.writeFileSync(combinedFile, JSON.stringify(allData, null, 2));
console.log(`\nCombined data saved to data/rgaa-all.json`);
