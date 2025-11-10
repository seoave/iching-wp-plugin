// build-zip.js
const fs = require('fs');
const archiver = require('archiver');
const path = require('path');

const PLUGIN_SLUG = 'iching-wp-plugin';
const ROOT_DIR = path.join(__dirname, '..');
const OUTPUT_PATH = path.join(ROOT_DIR, `${PLUGIN_SLUG}.zip`); // ZIP-файл у корені

// Файли/папки, які потрібно ігнорувати у фінальному ZIP-архіві
const IGNORE_LIST = [
  '.git',
  'node_modules',
  'package.json',
  'package-lock.json',
  'build-zip.js',
  'tools',
  'vendor',
  'idea',
];

// Перевіряємо, чи існує папка vendor. Якщо ні, то Composer не був запущений.
if (!fs.existsSync(path.join(ROOT_DIR, 'vendor'))) {
  console.error("❌ Папка 'vendor' не знайдена. Спочатку виконайте 'npm run build:deps' або 'composer install --no-dev'.");
  process.exit(1);
}

// Створюємо потік для запису ZIP-файлу
const output = fs.createWriteStream(OUTPUT_PATH);
const archive = archiver('zip', {
  zlib: { level: 9 } // Максимальний рівень стиснення
});

output.on('close', function() {
  console.log(`✅ Збірка успішно завершена! ${archive.pointer()} загальних байтів`);
  console.log(`Файл готовий до завантаження: ${PLUGIN_SLUG}.zip`);
});

archive.on('error', function(err) {
  throw err;
});

archive.pipe(output);

console.log(`--> Додавання файлів до ${PLUGIN_SLUG}...\n`);

// 1. Проходимо по кореневій папці та додаємо ВСІ файли/папки, окрім тих, що в IGNORE_LIST
const files = fs.readdirSync(ROOT_DIR);
files.forEach(file => {
  if (IGNORE_LIST.includes(file)) {
    return; // Пропускаємо
  }

  const filePath = path.join(ROOT_DIR, file);
  const destinationPath = path.join(PLUGIN_SLUG, file); // Включаємо папку плагіна у ZIP

  if (fs.statSync(filePath).isDirectory()) {
    // Додаємо папку (рекурсивно)
    archive.directory(filePath, destinationPath);
  } else {
    // Додаємо файл
    archive.file(filePath, { name: destinationPath });
  }
});

// 2. Додаємо vendor окремо (якщо не був ігнорований у files.forEach)
// Оскільки ми хочемо включити vendor, перевіряємо, чи він не був у ignore list
// і додаємо його, щоб гарантувати, що він додається з правильним шляхом: iching-wp-plugin/vendor
if (!IGNORE_LIST.includes('vendor')) {
  archive.directory(path.join(ROOT_DIR, 'vendor'), path.join(PLUGIN_SLUG, 'vendor'));
}


archive.finalize();
