"""Prepare a clean LOCAL test WordPress tree. Never use this on a production host."""
from pathlib import Path
import hashlib, json, shutil, subprocess, zipfile

helpers = Path(__file__).resolve().parent
cache = Path('/workspace/grant-wp-qa')
site = cache / 'repro-site'
if site.exists():
    raise SystemExit('Existing test tree preserved. Use resume.json and install-from-existing-files-if-needed; do not rerun bootstrap.json against an installed database.')
cache.mkdir(parents=True, exist_ok=True)
for name in ['package.json', 'package-lock.json']:
    target = cache / name
    supplied = (helpers / name).read_bytes()
    if target.exists() and target.read_bytes() != supplied:
        raise SystemExit('Existing CLI dependency files differ; review them before proceeding.')
    target.write_bytes(supplied)
if not (cache / 'node_modules/@wp-playground/cli/wp-playground.js').exists():
    subprocess.run(['npm', 'ci', '--prefix', str(cache), '--ignore-scripts', '--cache', '/workspace/grant-qa/npm-cache'], check=True)
for archive in json.loads((helpers / 'archives.json').read_text()):
    file = cache / archive['file']
    if not file.exists():
        subprocess.run(['curl', '--fail', '--silent', '--show-error', '--location', '--max-time', '120', archive['url'], '-o', str(file)], check=True)
    if hashlib.sha256(file.read_bytes()).hexdigest() != archive['sha256']:
        raise SystemExit('Archive digest mismatch: ' + file.name + '. No test tree was created.')
# Only verified official archives are unpacked. Never overwrite an existing tree.
site.mkdir()
with zipfile.ZipFile(cache / 'wordpress-7.0.7.zip') as archive:
    assert archive.testzip() is None
    for info in archive.infolist():
        if info.is_dir():
            continue
        relative = Path(info.filename).relative_to('wordpress')
        assert '..' not in relative.parts
        target = site / relative
        target.parent.mkdir(parents=True, exist_ok=True)
        target.write_bytes(archive.read(info))
for name in ['elementor.zip', 'wordpress-seo-28.6.zip']:
    with zipfile.ZipFile(cache / name) as archive:
        assert archive.testzip() is None
        archive.extractall(site / 'wp-content/plugins')
mu = site / 'wp-content/mu-plugins'
mu.mkdir(parents=True, exist_ok=True)
shutil.copy2(helpers / 'grant-qa.php', mu / 'grant-qa.php')
for name in ['qa-repair.php', 'qa-reset.php']:
    shutil.copy2(helpers / name, site / name)
print('Clean isolated WordPress tree prepared. Start the documented loopback server with bootstrap.json once. The MU plugin blocks provider HTTP and email; never deploy these QA helpers.')
