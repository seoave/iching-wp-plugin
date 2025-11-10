/* Build plugin for production */
const fs = require('fs');
const archiver = require('archiver');
const path = require('path');

const PLUGIN_SLUG = 'iching-wp-plugin';
const ROOT_DIR = path.join(__dirname, '..');
const OUTPUT_PATH = path.join(ROOT_DIR, `${PLUGIN_SLUG}.zip`); // ZIP-файл у корені

const IGNORE_LIST = [
  '.git',
  'node_modules',
  'package.json',
  'package-lock.json',
  'build-zip.js',
  'tools',
  '.idea',
  '.vscode',
];

// Do /vendor/ exists?
if (!fs.existsSync(path.join(ROOT_DIR, 'vendor'))) {
  console.error('❌ \'vendor\' directory not found. First run \'npm run build:deps\' or ' +
    ' \'composer install --no-dev\'.');
  process.exit(1);
}

// Create stream to write ZIP-file
const output = fs.createWriteStream(OUTPUT_PATH);
const archive = archiver('zip', {
  zlib: { level: 9 }
});

output.on('close', function () {
  console.log(`✅ Build completed! ${archive.pointer()} bites`);
  console.log(`File is ready to download: ${PLUGIN_SLUG}.zip`);
});

archive.on('error', function (err) {
  throw err;
});

archive.pipe(output);

console.log(`--> Adding files to ${PLUGIN_SLUG}...\n`);

// 1. Loop over root folder adding all files except IGNORE_LIST
const files = fs.readdirSync(ROOT_DIR);
files.forEach(file => {
  if (IGNORE_LIST.includes(file)) {
    return;
  }

  const filePath = path.join(ROOT_DIR, file);
  const destinationPath = path.join(PLUGIN_SLUG, file);

  if (fs.statSync(filePath).isDirectory()) {
    archive.directory(filePath, destinationPath);
  } else {
    archive.file(filePath, { name: destinationPath });
  }
});

// 2. Add vendor
if (!IGNORE_LIST.includes('vendor')) {
  archive.directory(path.join(ROOT_DIR, 'vendor'), path.join(PLUGIN_SLUG, 'vendor'));
}

archive.finalize();
