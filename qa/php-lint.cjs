const fs = require('node:fs');
const path = require('node:path');
const { spawnSync } = require('node:child_process');

const plugin = path.resolve(__dirname, '../grant-publishing-site');
const cli = path.join(__dirname, 'php.mjs');
const output = path.join(__dirname, 'php-lint-results.json');
function files(dir) {
  return fs.readdirSync(dir, { withFileTypes: true }).flatMap(entry => {
    const file = path.join(dir, entry.name);
    return entry.isDirectory() ? files(file) : entry.name.endsWith('.php') ? [file] : [];
  });
}
const results = [];
for (const version of ['7.4', '8.2']) {
  for (const file of files(plugin)) {
    const run = spawnSync(process.execPath, [cli, '-l', file], {
      encoding: 'utf8', env: { ...process.env, PHP: version }, timeout: 30000,
    });
    results.push({
      version, file: path.relative(plugin, file), exitCode: run.status,
      pass: run.status === 0 && /No syntax errors detected/.test(run.stdout),
      stdout: run.stdout.trim(), stderr: run.stderr.trim(),
    });
  }
}
fs.writeFileSync(output, JSON.stringify({
  runtime: 'Official WordPress Playground PHP.wasm CLI 3.1.57',
  results,
}, null, 2) + '\n');
for (const result of results) console.log(`${result.pass ? 'PASS' : 'FAIL'} PHP ${result.version}: ${result.file}`);
process.exit(results.every(result => result.pass) ? 0 : 1);
