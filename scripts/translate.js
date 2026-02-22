#!/usr/bin/env node
/**
 * Translation helper script
 *
 * Compares French and English language files, identifies missing translations,
 * and can generate initial auto-translations (marked for human review).
 *
 * Usage:
 *   node scripts/translate.js status    - Show translation status
 *   node scripts/translate.js missing   - List missing English translations
 *   node scripts/translate.js validate <key> - Mark a translation as validated
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const ROOT = path.resolve(__dirname, '..');
const STATUS_FILE = path.join(ROOT, 'lang/en.status.json');

function loadPhpArray(file) {
	const script = `echo json_encode(require '${file}');`;
	const result = execSync(`php -r "${script}"`, { encoding: 'utf-8' });
	return JSON.parse(result);
}

function flatKeys(obj, prefix = '') {
	const keys = [];
	for (const [k, v] of Object.entries(obj)) {
		const key = prefix ? `${prefix}.${k}` : k;
		if (typeof v === 'object' && v !== null) {
			keys.push(...flatKeys(v, key));
		} else {
			keys.push(key);
		}
	}
	return keys;
}

function getNestedValue(obj, dotKey) {
	return dotKey.split('.').reduce((o, k) => o?.[k], obj);
}

const command = process.argv[2] || 'status';

const fr = loadPhpArray(path.join(ROOT, 'lang/fr.php'));
const en = loadPhpArray(path.join(ROOT, 'lang/en.php'));
const frKeys = flatKeys(fr);
const enKeys = flatKeys(en);

const status = fs.existsSync(STATUS_FILE)
	? JSON.parse(fs.readFileSync(STATUS_FILE, 'utf-8'))
	: {};

switch (command) {
	case 'status': {
		const missing = frKeys.filter(k => !enKeys.includes(k));
		const auto = Object.entries(status).filter(([, v]) => v === 'auto');
		const validated = Object.entries(status).filter(([, v]) => v === 'validated');

		console.log('Translation Status');
		console.log('==================');
		console.log(`French keys:    ${frKeys.length}`);
		console.log(`English keys:   ${enKeys.length}`);
		console.log(`Missing:        ${missing.length}`);
		console.log(`Auto-translated: ${auto.length}`);
		console.log(`Validated:      ${validated.length}`);

		if (missing.length > 0) {
			console.log('\nMissing keys:');
			missing.forEach(k => console.log(`  - ${k}: "${getNestedValue(fr, k)}"`));
		}

		if (auto.length > 0) {
			console.log('\nPending validation:');
			auto.forEach(([k]) => console.log(`  - ${k}: "${getNestedValue(en, k)}"`));
		}
		break;
	}

	case 'missing': {
		const missing = frKeys.filter(k => !enKeys.includes(k));
		if (missing.length === 0) {
			console.log('All French keys have English translations.');
		} else {
			console.log('Missing English translations:');
			missing.forEach(k => {
				console.log(`  ${k}: "${getNestedValue(fr, k)}"`);
			});
		}
		break;
	}

	case 'validate': {
		const key = process.argv[3];
		if (!key) {
			console.error('Usage: node scripts/translate.js validate <key>');
			process.exit(1);
		}
		if (!enKeys.includes(key)) {
			console.error(`Key "${key}" not found in English translations.`);
			process.exit(1);
		}
		status[key] = 'validated';
		// Remove _comment from iteration
		const { _comment, ...rest } = status;
		const sorted = { _comment, ...Object.fromEntries(Object.entries(rest).sort()) };
		fs.writeFileSync(STATUS_FILE, JSON.stringify(sorted, null, 2) + '\n');
		console.log(`Marked "${key}" as validated.`);
		break;
	}

	default:
		console.error(`Unknown command: ${command}`);
		console.error('Usage: node scripts/translate.js [status|missing|validate <key>]');
		process.exit(1);
}
