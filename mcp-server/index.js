#!/usr/bin/env node
/**
 * RGAA MCP Server
 *
 * Model Context Protocol server that exposes RGAA accessibility criteria,
 * glossary, and reference data as searchable tools and resources.
 *
 * Usage:
 *   node mcp-server/index.js
 *
 * MCP Configuration (claude_desktop_config.json):
 *   {
 *     "mcpServers": {
 *       "rgaa": {
 *         "command": "node",
 *         "args": ["/path/to/Rgaa_Website/mcp-server/index.js"]
 *       }
 *     }
 *   }
 */

const { Server } = require('@modelcontextprotocol/sdk/server/index.js');
const { StdioServerTransport } = require('@modelcontextprotocol/sdk/server/stdio.js');
const {
	CallToolRequestSchema,
	ListToolsRequestSchema,
	ListResourcesRequestSchema,
	ReadResourceRequestSchema,
} = require('@modelcontextprotocol/sdk/types.js');
const fs = require('fs');
const path = require('path');

const DATA_DIR = path.resolve(__dirname, '..', 'data');

// Load RGAA data
function loadData() {
	const dataFile = path.join(DATA_DIR, 'rgaa-all.json');
	if (!fs.existsSync(dataFile)) {
		console.error('Data file not found. Run: node scripts/extract-rgaa-data.js');
		process.exit(1);
	}
	return JSON.parse(fs.readFileSync(dataFile, 'utf-8'));
}

const data = loadData();

/**
 * Normalize French text: remove accents/diacritics and lowercase.
 * "contrasté" → "contraste", "éléments" → "elements"
 */
function normalizeAccents(text) {
	return text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

/**
 * Simple French stemmer - strips common inflectional suffixes
 * to find word roots for variant matching.
 * "contrastées" → "contrast", "contraster" → "contrast", "images" → "imag"
 */
function frenchStem(word) {
	if (word.length < 5) return word;

	const suffixes = [
		'issements', 'issement',
		'ements', 'ement',
		'antes', 'ante', 'ants', 'ant',
		'euses', 'euse', 'eurs', 'eur',
		'ibles', 'ible', 'ables', 'able',
		'ations', 'ation', 'tions', 'tion',
		'ives', 'ive',
		'ees', 'ee',
		'er', 'ez',
		'es',
		'e', 's',
	];

	for (const suffix of suffixes) {
		if (word.endsWith(suffix) && word.length - suffix.length >= 4) {
			return word.slice(0, -suffix.length);
		}
	}

	return word;
}

/**
 * French-aware text matching: handles accent variants and word inflections.
 * "contraste" matches text containing "contrasté", "contrastées", "contraster", etc.
 */
function frenchMatch(text, query) {
	const normalizedText = normalizeAccents(text);
	const normalizedQuery = normalizeAccents(query);

	// 1. Direct substring match after accent normalization (handles most cases)
	if (normalizedText.includes(normalizedQuery)) return true;

	// 2. Stem-based matching: each query word (≥3 chars) must match a text word by stem
	const queryWords = normalizedQuery.split(/\s+/).filter(w => w.length >= 3);
	if (queryWords.length === 0) return false;

	const textWords = normalizedText.split(/[^a-z0-9]+/).filter(w => w.length >= 2);

	return queryWords.every(qw => {
		const qStem = frenchStem(qw);
		return textWords.some(tw => {
			const tStem = frenchStem(tw);
			// Match if stems are equal, or one is a prefix of the other (min 4 chars)
			return qStem === tStem ||
				(qStem.length >= 4 && tStem.startsWith(qStem)) ||
				(tStem.length >= 4 && qStem.startsWith(tStem));
		});
	});
}

// Create MCP server
const server = new Server(
	{
		name: 'rgaa-server',
		version: '1.0.0',
	},
	{
		capabilities: {
			tools: {},
			resources: {},
		},
	}
);

/**
 * Tool: search_criteria
 */
function searchCriteria(query, version = 'rgaa41', level = null) {
	const versionData = data.versions[version];
	if (!versionData) return { error: `Unknown version: ${version}. Available: ${Object.keys(data.versions).join(', ')}` };

	// 1. Search in criteria titles/themes AND in test descriptions/methodologies
	const directResults = new Set();
	let results = [];
	for (const c of versionData.criteria) {
		const criterionText = `${c.title} ${c.number} ${c.theme}`;
		const criterionMatch = frenchMatch(criterionText, query);

		// Search in tests (description + methodology)
		const matchingTests = c.tests.filter(t => {
			const testText = `${t.description || ''} ${t.methodology || ''}`;
			return frenchMatch(testText, query);
		});

		if (criterionMatch || matchingTests.length > 0) {
			directResults.add(c.number);
			results.push({
				...c,
				_matchingTests: matchingTests.length > 0 && !criterionMatch
					? matchingTests.map(t => t.number)
					: undefined,
			});
		}
	}

	// 2. Also search glossary: find matching terms and their linked criteria
	const matchingGlossaryTerms = versionData.glossary.filter(t => {
		return frenchMatch(t.term, query) || frenchMatch(t.definition, query);
	});

	// Collect criteria found via glossary (not already in direct results)
	const glossaryCriteriaMap = {};
	for (const term of matchingGlossaryTerms) {
		for (const lc of (term.linkedCriteria || [])) {
			if (!directResults.has(lc.number)) {
				if (!glossaryCriteriaMap[lc.number]) {
					glossaryCriteriaMap[lc.number] = { criterion: lc, viaTerms: [] };
				}
				glossaryCriteriaMap[lc.number].viaTerms.push(term.term);
			}
		}
	}

	// Add glossary-linked criteria to results
	const glossaryCriteria = Object.values(glossaryCriteriaMap).map(({ criterion, viaTerms }) => {
		const fullCriterion = versionData.criteria.find(c => c.number === criterion.number);
		return {
			number: criterion.number,
			level: criterion.level,
			title: fullCriterion ? fullCriterion.title : criterion.title,
			theme: fullCriterion ? fullCriterion.theme : '',
			testCount: fullCriterion ? fullCriterion.tests.length : criterion.tests.length,
			foundViaGlossary: [...new Set(viaTerms)],
		};
	});

	if (level) {
		results = results.filter(c => c.level === level.toUpperCase());
		glossaryCriteria.splice(0, glossaryCriteria.length,
			...glossaryCriteria.filter(c => c.level === level.toUpperCase()));
	}

	return {
		version: versionData.label,
		query,
		resultCount: results.length + glossaryCriteria.length,
		criteria: results.map(c => ({
			number: c.number,
			level: c.level,
			title: c.title,
			theme: c.theme,
			testCount: c.tests.length,
			matchingTests: c._matchingTests,
		})),
		criteriaViaGlossary: glossaryCriteria.length > 0 ? glossaryCriteria : undefined,
		matchingGlossaryTerms: matchingGlossaryTerms.length > 0
			? matchingGlossaryTerms.map(t => ({
				term: t.term,
				linkedCriteriaCount: (t.linkedCriteria || []).length,
			}))
			: undefined,
	};
}

/**
 * Tool: get_criterion
 */
function getCriterion(number, version = 'rgaa41') {
	const versionData = data.versions[version];
	if (!versionData) return { error: `Unknown version: ${version}` };

	const criterion = versionData.criteria.find(c => c.number === number);
	if (!criterion) return { error: `Criterion ${number} not found in ${version}` };

	return {
		version: versionData.label,
		...criterion,
	};
}

/**
 * Tool: list_themes
 */
function listThemes(version = 'rgaa41') {
	const versionData = data.versions[version];
	if (!versionData) return { error: `Unknown version: ${version}` };

	const themes = {};
	for (const c of versionData.criteria) {
		if (!themes[c.themeId]) {
			themes[c.themeId] = { id: c.themeId, name: c.theme, criteriaCount: 0, levels: { A: 0, AA: 0, AAA: 0 } };
		}
		themes[c.themeId].criteriaCount++;
		if (themes[c.themeId].levels[c.level] !== undefined) {
			themes[c.themeId].levels[c.level]++;
		}
	}

	return {
		version: versionData.label,
		themes: Object.values(themes),
		stats: versionData.stats,
	};
}

/**
 * Tool: search_glossary
 */
function searchGlossary(query, version = 'rgaa41') {
	const versionData = data.versions[version];
	if (!versionData) return { error: `Unknown version: ${version}` };

	const results = versionData.glossary.filter(t => {
		return frenchMatch(t.term, query) || frenchMatch(t.definition, query);
	});

	return {
		version: versionData.label,
		query,
		resultCount: results.length,
		terms: results,
	};
}

/**
 * Tool: search_tests
 */
function searchTests(query, version = 'rgaa41', level = null) {
	const versionData = data.versions[version];
	if (!versionData) return { error: `Unknown version: ${version}. Available: ${Object.keys(data.versions).join(', ')}` };

	const results = [];
	for (const c of versionData.criteria) {
		if (level && c.level !== level.toUpperCase()) continue;

		for (const t of c.tests) {
			const testText = `${t.description || ''} ${t.methodology || ''}`;
			if (frenchMatch(testText, query)) {
				results.push({
					number: t.number,
					description: t.description,
					methodology: t.methodology || undefined,
					glossaryRefs: t.glossaryRefs && t.glossaryRefs.length > 0
						? t.glossaryRefs.map(refId => {
							const term = versionData.glossary.find(g => g.id === refId);
							return term ? term.term : refId;
						})
						: undefined,
					criterion: {
						number: c.number,
						title: c.title,
						level: c.level,
						theme: c.theme,
					},
				});
			}
		}
	}

	return {
		version: versionData.label,
		query,
		resultCount: results.length,
		tests: results,
	};
}

/**
 * Tool: get_criteria_by_theme
 */
function getCriteriaByTheme(themeId, version = 'rgaa41') {
	const versionData = data.versions[version];
	if (!versionData) return { error: `Unknown version: ${version}` };

	const criteria = versionData.criteria.filter(c => c.themeId === themeId);
	if (criteria.length === 0) {
		return { error: `No criteria found for theme "${themeId}" in ${version}. Available themes: ${versionData.themes.join(', ')}` };
	}

	return {
		version: versionData.label,
		theme: criteria[0].theme,
		themeId,
		criteriaCount: criteria.length,
		criteria: criteria.map(c => ({
			number: c.number,
			level: c.level,
			title: c.title,
			testCount: c.tests.length,
		})),
	};
}

/**
 * Tool: get_stats
 */
function getStats(version = null) {
	if (version) {
		const versionData = data.versions[version];
		if (!versionData) return { error: `Unknown version: ${version}` };
		return { version: versionData.label, stats: versionData.stats };
	}

	const allStats = {};
	for (const [v, d] of Object.entries(data.versions)) {
		allStats[v] = { label: d.label, stats: d.stats };
	}
	return { versions: allStats };
}

// Register tools
server.setRequestHandler(ListToolsRequestSchema, async () => ({
	tools: [
		{
			name: 'search_criteria',
			description: 'Search RGAA accessibility criteria by keyword. Searches in criterion titles, themes, AND in test descriptions/methodologies. Also automatically searches the glossary and returns additional criteria linked via glossary terms. Supports French variants (accent-insensitive, inflections). Returns criteria (with matchingTests when found via tests only), criteria found via glossary, and matching glossary terms.',
			inputSchema: {
				type: 'object',
				properties: {
					query: { type: 'string', description: 'Search query (matches against criterion title, number, theme)' },
					version: { type: 'string', description: 'RGAA version: rgaa41 (default), rgaa4, rgaa3', default: 'rgaa41' },
					level: { type: 'string', description: 'Filter by WCAG level: A, AA, AAA', enum: ['A', 'AA', 'AAA'] },
				},
				required: ['query'],
			},
		},
		{
			name: 'get_criterion',
			description: 'Get full details of a specific RGAA criterion by its number (e.g., "1.1", "4.3"). Includes all tests.',
			inputSchema: {
				type: 'object',
				properties: {
					number: { type: 'string', description: 'Criterion number (e.g., "1.1", "4.3", "11.2")' },
					version: { type: 'string', description: 'RGAA version: rgaa41 (default), rgaa4, rgaa3', default: 'rgaa41' },
				},
				required: ['number'],
			},
		},
		{
			name: 'list_themes',
			description: 'List all RGAA themes (Images, Frames, Colors, etc.) with criteria counts per level.',
			inputSchema: {
				type: 'object',
				properties: {
					version: { type: 'string', description: 'RGAA version: rgaa41 (default), rgaa4, rgaa3', default: 'rgaa41' },
				},
			},
		},
		{
			name: 'get_criteria_by_theme',
			description: 'Get all criteria for a given theme (e.g., "images", "formulaires", "liens").',
			inputSchema: {
				type: 'object',
				properties: {
					themeId: { type: 'string', description: 'Theme ID (e.g., "images", "cadres", "couleurs", "multimedia", "tableaux", "liens", "scripts", "elements", "structure", "presentation", "formulaires", "navigation", "consultation")' },
					version: { type: 'string', description: 'RGAA version: rgaa41 (default), rgaa4, rgaa3', default: 'rgaa41' },
				},
				required: ['themeId'],
			},
		},
		{
			name: 'search_tests',
			description: 'Search RGAA tests by keyword. Searches in test descriptions and methodologies. Returns matching tests with their full content, linked glossary terms, and parent criterion. Useful for technical searches (e.g. "aria-hidden", "fieldset", "alt", "tabindex"). Supports French variants (accent-insensitive, inflections).',
			inputSchema: {
				type: 'object',
				properties: {
					query: { type: 'string', description: 'Search query (matches against test description and methodology)' },
					version: { type: 'string', description: 'RGAA version: rgaa41 (default), rgaa4, rgaa3', default: 'rgaa41' },
					level: { type: 'string', description: 'Filter by WCAG level: A, AA, AAA', enum: ['A', 'AA', 'AAA'] },
				},
				required: ['query'],
			},
		},
		{
			name: 'search_glossary',
			description: 'Search the RGAA glossary for accessibility terms and definitions. Each term includes linkedCriteria: the list of criteria and tests that reference it. Supports French variants (accent-insensitive, inflections).',
			inputSchema: {
				type: 'object',
				properties: {
					query: { type: 'string', description: 'Search query (matches term name and definition)' },
					version: { type: 'string', description: 'RGAA version: rgaa41 (default), rgaa4, rgaa3', default: 'rgaa41' },
				},
				required: ['query'],
			},
		},
		{
			name: 'get_stats',
			description: 'Get statistics about RGAA criteria (total count, by level, by theme).',
			inputSchema: {
				type: 'object',
				properties: {
					version: { type: 'string', description: 'RGAA version (omit for all versions)' },
				},
			},
		},
	],
}));

// Handle tool calls
server.setRequestHandler(CallToolRequestSchema, async (request) => {
	const { name, arguments: args } = request.params;

	let result;
	switch (name) {
		case 'search_criteria':
			result = searchCriteria(args.query, args.version, args.level);
			break;
		case 'get_criterion':
			result = getCriterion(args.number, args.version);
			break;
		case 'list_themes':
			result = listThemes(args.version);
			break;
		case 'get_criteria_by_theme':
			result = getCriteriaByTheme(args.themeId, args.version);
			break;
		case 'search_tests':
			result = searchTests(args.query, args.version, args.level);
			break;
		case 'search_glossary':
			result = searchGlossary(args.query, args.version);
			break;
		case 'get_stats':
			result = getStats(args.version);
			break;
		default:
			result = { error: `Unknown tool: ${name}` };
	}

	return {
		content: [{ type: 'text', text: JSON.stringify(result, null, 2) }],
	};
});

// Register resources
server.setRequestHandler(ListResourcesRequestSchema, async () => ({
	resources: [
		{
			uri: 'rgaa://versions',
			name: 'RGAA Versions',
			description: 'List of available RGAA versions with statistics',
			mimeType: 'application/json',
		},
		...Object.entries(data.versions).map(([id, v]) => ({
			uri: `rgaa://${id}/criteria`,
			name: `${v.label} Criteria`,
			description: `All ${v.stats.totalCriteria} criteria for ${v.label}`,
			mimeType: 'application/json',
		})),
		...Object.entries(data.versions).map(([id, v]) => ({
			uri: `rgaa://${id}/glossary`,
			name: `${v.label} Glossary`,
			description: `All ${v.stats.totalGlossaryTerms} glossary terms for ${v.label}`,
			mimeType: 'application/json',
		})),
	],
}));

// Handle resource reads
server.setRequestHandler(ReadResourceRequestSchema, async (request) => {
	const { uri } = request.params;

	if (uri === 'rgaa://versions') {
		return {
			contents: [{
				uri,
				mimeType: 'application/json',
				text: JSON.stringify(getStats(), null, 2),
			}],
		};
	}

	const match = uri.match(/^rgaa:\/\/(\w+)\/(criteria|glossary)$/);
	if (match) {
		const [, version, type] = match;
		const versionData = data.versions[version];
		if (versionData) {
			return {
				contents: [{
					uri,
					mimeType: 'application/json',
					text: JSON.stringify(versionData[type], null, 2),
				}],
			};
		}
	}

	throw new Error(`Resource not found: ${uri}`);
});

// Start the server
async function main() {
	const transport = new StdioServerTransport();
	await server.connect(transport);
	console.error('RGAA MCP Server running on stdio');
}

main().catch(console.error);
