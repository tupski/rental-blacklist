<?php

/**
 * Build script untuk production tanpa Vite
 * Jalankan: php build-for-production.php
 */

echo "🚀 Building for production...\n\n";

// 1. Clear cache
echo "1. Clearing cache...\n";
exec('php artisan cache:clear');
exec('php artisan config:clear');
exec('php artisan route:clear');
exec('php artisan view:clear');
echo "   ✅ Cache cleared\n\n";

// 2. Optimize for production
echo "2. Optimizing for production...\n";
exec('php artisan config:cache');
exec('php artisan route:cache');
exec('php artisan view:cache');
echo "   ✅ Optimized\n\n";

// 3. Create symbolic link for storage
echo "3. Creating storage link...\n";
exec('php artisan storage:link');
echo "   ✅ Storage linked\n\n";

// 4. Set proper permissions
echo "4. Setting permissions...\n";
if (PHP_OS_FAMILY !== 'Windows') {
    exec('chmod -R 755 storage');
    exec('chmod -R 755 bootstrap/cache');
    echo "   ✅ Permissions set\n\n";
} else {
    echo "   ⚠️  Windows detected - set permissions manually\n\n";
}

// 5. Create dummy manifest for Vite
echo "5. Creating dummy Vite manifest...\n";
$manifestDir = 'public/build';
$manifestFile = $manifestDir . '/manifest.json';

if (!is_dir($manifestDir)) {
    mkdir($manifestDir, 0755, true);
}

$dummyManifest = [
    "resources/css/app.css" => [
        "file" => "assets/app.css",
        "isEntry" => true,
        "src" => "resources/css/app.css"
    ],
    "resources/js/app.js" => [
        "file" => "assets/app.js",
        "isEntry" => true,
        "src" => "resources/js/app.js"
    ]
];

file_put_contents($manifestFile, json_encode($dummyManifest, JSON_PRETTY_PRINT));
echo "   ✅ Dummy manifest created\n\n";

// 6. Create dummy CSS and JS files
echo "6. Creating dummy assets...\n";
$assetsDir = 'public/build/assets';
if (!is_dir($assetsDir)) {
    mkdir($assetsDir, 0755, true);
}

// Dummy CSS
file_put_contents($assetsDir . '/app.css', '/* Dummy CSS file for production */');

// Dummy JS
file_put_contents($assetsDir . '/app.js', '// Dummy JS file for production');
echo "   ✅ Dummy assets created\n\n";

echo "🎉 Production build completed!\n\n";
echo "📋 Next steps:\n";
echo "1. Upload semua file ke cPanel\n";
echo "2. Set document root ke folder 'public'\n";
echo "3. Import database\n";
echo "4. Set environment variables di .env\n";
echo "5. Run: php artisan migrate --force\n";
echo "6. Run: php artisan db:seed --force\n\n";

echo "🔧 Troubleshooting:\n";
echo "- Jika masih error Vite: hapus @vite directive di layout\n";
echo "- Jika route error: php artisan route:cache\n";
echo "- Jika permission error: chmod 755 storage bootstrap/cache\n\n";
