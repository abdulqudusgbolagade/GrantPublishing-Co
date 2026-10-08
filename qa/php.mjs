import { PHP } from '@php-wasm/universal';
import { loadNodeRuntime, useHostFilesystem } from '@php-wasm/node';

// Official PHP.wasm CLI currently discards ordinary PHP exit codes. This small
// local runner preserves exit status so failures cannot appear successful.
const version = process.env.PHP || '8.2';
const php = new PHP(await loadNodeRuntime(version, { emscriptenOptions: { processId: 1 } }));
useHostFilesystem(php);
const result = await php.cli(['php', ...process.argv.slice(2)]);
const stdio = Promise.all([
  result.stdout.pipeTo(new WritableStream({ write(bytes) { process.stdout.write(bytes); } })),
  result.stderr.pipeTo(new WritableStream({ write(bytes) { process.stderr.write(bytes); } })),
]);
let status;
try {
  status = await result.exitCode;
  await stdio;
} catch (error) {
  process.stderr.write(String(error) + '\n');
  status = typeof error.status === 'number' ? error.status : 1;
}
process.exit(status);
