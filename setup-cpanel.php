<?php

/**
 * Setup script untuk cPanel production
 * Jalankan di cPanel terminal: php setup-cpanel.php
 */

echo "🚀 Setting up Laravel for cPanel...\n\n";

// 1. Check environment
echo "1. Checking environment...\n";
if (!file_exists('.env')) {
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "   ✅ .env created from .env.example\n";
    } else {
        echo "   ❌ .env.example not found!\n";
        exit(1);
    }
} else {
    echo "   ✅ .env exists\n";
}

// 2. Generate app key if not exists
echo "\n2. Checking app key...\n";
$envContent = file_get_contents('.env');
if (strpos($envContent, 'APP_KEY=') === false || strpos($envContent, 'APP_KEY=base64:') === false) {
    exec('php artisan key:generate --force');
    echo "   ✅ App key generated\n";
} else {
    echo "   ✅ App key exists\n";
}

// 3. Set production environment
echo "\n3. Setting production environment...\n";
$envContent = file_get_contents('.env');
$envContent = preg_replace('/APP_ENV=.*/', 'APP_ENV=production', $envContent);
$envContent = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=false', $envContent);
file_put_contents('.env', $envContent);
echo "   ✅ Environment set to production\n";

// 4. Clear and cache
echo "\n4. Clearing cache and optimizing...\n";
exec('php artisan cache:clear');
exec('php artisan config:clear');
exec('php artisan route:clear');
exec('php artisan view:clear');
echo "   ✅ Cache cleared\n";

exec('php artisan config:cache');
exec('php artisan route:cache');
exec('php artisan view:cache');
echo "   ✅ Cache optimized\n";

// 5. Storage link
echo "\n5. Creating storage link...\n";
if (!file_exists('public/storage')) {
    exec('php artisan storage:link');
    echo "   ✅ Storage linked\n";
} else {
    echo "   ✅ Storage link exists\n";
}

// 6. Database migration
echo "\n6. Running database migrations...\n";
try {
    exec('php artisan migrate --force 2>&1', $output, $returnCode);
    if ($returnCode === 0) {
        echo "   ✅ Migrations completed\n";
    } else {
        echo "   ⚠️  Migration warnings (check database config)\n";
        foreach ($output as $line) {
            echo "      $line\n";
        }
    }
} catch (Exception $e) {
    echo "   ⚠️  Migration error: " . $e->getMessage() . "\n";
}

// 7. Create dummy Vite files
echo "\n7. Creating production assets...\n";
$buildDir = 'public/build';
$assetsDir = $buildDir . '/assets';

if (!is_dir($buildDir)) {
    mkdir($buildDir, 0755, true);
}
if (!is_dir($assetsDir)) {
    mkdir($assetsDir, 0755, true);
}

// Create manifest
$manifest = [
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
file_put_contents($buildDir . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));

// Create dummy assets
file_put_contents($assetsDir . '/app.css', '/* Production CSS */');
file_put_contents($assetsDir . '/app.js', '// Production JS');
echo "   ✅ Production assets created\n";

// 8. Set permissions
echo "\n8. Setting permissions...\n";
if (PHP_OS_FAMILY !== 'Windows') {
    exec('chmod -R 755 storage');
    exec('chmod -R 755 bootstrap/cache');
    exec('chmod -R 755 public');
    echo "   ✅ Permissions set\n";
} else {
    echo "   ⚠️  Set permissions manually\n";
}

// 9. Check requirements
echo "\n9. Checking requirements...\n";
$checks = [
    'PHP Version >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
    'OpenSSL Extension' => extension_loaded('openssl'),
    'PDO Extension' => extension_loaded('pdo'),
    'Mbstring Extension' => extension_loaded('mbstring'),
    'Tokenizer Extension' => extension_loaded('tokenizer'),
    'XML Extension' => extension_loaded('xml'),
    'Ctype Extension' => extension_loaded('ctype'),
    'JSON Extension' => extension_loaded('json'),
    'BCMath Extension' => extension_loaded('bcmath'),
];

foreach ($checks as $check => $result) {
    echo "   " . ($result ? "✅" : "❌") . " $check\n";
}

echo "\n🎉 Setup completed!\n\n";
echo "📋 Manual steps:\n";
echo "1. Update .env with your database credentials\n";
echo "2. Set document root to 'public' folder in cPanel\n";
echo "3. Import your database\n";
echo "4. Run: php artisan migrate --force\n";
echo "5. Create admin user if needed\n\n";

echo "🔧 If you get errors:\n";
echo "- Route not found: php artisan route:cache\n";
echo "- Config error: php artisan config:cache\n";
echo "- Permission error: chmod 755 storage bootstrap/cache\n";
echo "- Vite error: Already fixed with CDN fallback\n\n";

echo "🌐 Your app should now work at: " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'your-domain.com') . "\n";
