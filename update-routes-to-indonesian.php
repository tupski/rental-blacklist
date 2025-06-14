<?php

/**
 * Script untuk mengupdate semua route references ke bahasa Indonesia
 * 
 * Usage: php update-routes-to-indonesian.php
 */

// Route mapping dari English ke Indonesian
$routeMapping = [
    // Main routes
    'home' => 'beranda',
    'dashboard' => 'dasbor',
    'login' => 'masuk',
    'register' => 'daftar',
    'logout' => 'keluar',
    
    // Public routes
    'public.search' => 'publik.cari',
    'public.detail' => 'publik.detail',
    'public.unlock' => 'publik.buka',
    'public.full-detail' => 'publik.detail-lengkap',
    'public.print-detail' => 'publik.cetak-detail',
    'public.download-pdf' => 'publik.unduh-pdf',
    
    // Profile routes
    'profile.edit' => 'profil.edit',
    'profile.update' => 'profil.perbarui',
    'profile.destroy' => 'profil.hapus',
    
    // Report routes
    'report.create' => 'laporan.buat',
    'report.store' => 'laporan.simpan',
    
    // Rental routes
    'rentals.index' => 'rental.indeks',
    'rental.register' => 'rental.daftar',
    'rental.store' => 'rental.simpan',
    'rental.dashboard' => 'rental.dasbor',
    
    // Dashboard routes
    'dashboard.blacklist.index' => 'dasbor.daftar-hitam.indeks',
    'dashboard.blacklist.create' => 'dasbor.daftar-hitam.buat',
    'dashboard.blacklist.store' => 'dasbor.daftar-hitam.simpan',
    'dashboard.blacklist.show' => 'dasbor.daftar-hitam.tampil',
    'dashboard.blacklist.edit' => 'dasbor.daftar-hitam.edit',
    'dashboard.blacklist.update' => 'dasbor.daftar-hitam.perbarui',
    'dashboard.blacklist.destroy' => 'dasbor.daftar-hitam.hapus',
    'dashboard.blacklist.search' => 'dasbor.daftar-hitam.cari',
    
    // User routes
    'user.dashboard' => 'pengguna.dasbor',
    'user.search' => 'pengguna.cari',
    'user.unlock' => 'pengguna.buka',
    
    // Admin routes
    'admin.dashboard' => 'admin.dasbor',
    
    // API routes
    'api.docs' => 'api.dokumentasi',
    'api-key.show' => 'kunci-api.tampil',
    'api-key.generate' => 'kunci-api.buat',
    'api-key.reset' => 'kunci-api.reset',
    
    // Topup routes
    'topup.index' => 'isi-saldo.indeks',
    'topup.create' => 'isi-saldo.buat',
    'topup.store' => 'isi-saldo.simpan',
    'topup.confirm' => 'isi-saldo.konfirmasi',
    'topup.upload-proof' => 'isi-saldo.unggah-bukti',
    'balance.history' => 'saldo.riwayat',
    
    // Invoice routes
    'invoice.show' => 'faktur.tampil',
    'invoice.download' => 'faktur.unduh',
    
    // Sponsor routes
    'sponsors.index' => 'sponsor.indeks',
    'sponsors.sponsorship' => 'sponsor.kemitraan',
    
    // Auth routes
    'password.request' => 'kata-sandi.permintaan',
    'password.email' => 'kata-sandi.email',
    'password.reset' => 'kata-sandi.reset',
    'password.store' => 'kata-sandi.simpan',
    'password.confirm' => 'kata-sandi.konfirmasi',
    'password.update' => 'kata-sandi.perbarui',
    'verification.notice' => 'verifikasi.pemberitahuan',
    'verification.verify' => 'verifikasi.verifikasi',
    'verification.send' => 'verifikasi.kirim',
];

// Parameter mapping
$parameterMapping = [
    'search' => 'cari',
    'q' => 'cari',
    'query' => 'kueri',
    'page' => 'halaman',
    'per_page' => 'per_halaman',
    'sort' => 'urutkan',
    'order' => 'urutan',
    'filter' => 'saring',
    'category' => 'kategori',
    'status' => 'status',
    'type' => 'jenis',
];

// Directories to scan
$directories = [
    'resources/views',
    'app/Http/Controllers',
    'app/Http/Requests',
    'app/Models',
    'routes',
    'tests',
];

// File extensions to process
$extensions = ['php', 'blade.php', 'js', 'vue'];

function updateRouteReferences($content, $routeMapping, $parameterMapping) {
    // Update route() calls
    foreach ($routeMapping as $old => $new) {
        // Match route('old.route') and route("old.route")
        $content = preg_replace(
            "/route\s*\(\s*['\"]" . preg_quote($old, '/') . "['\"]\s*\)/",
            "route('{$new}')",
            $content
        );
        
        // Match route('old.route', $params) and route("old.route", $params)
        $content = preg_replace(
            "/route\s*\(\s*['\"]" . preg_quote($old, '/') . "['\"]\s*,/",
            "route('{$new}',",
            $content
        );
    }
    
    // Update parameter names in forms and requests
    foreach ($parameterMapping as $old => $new) {
        // Update name="old" to name="new"
        $content = preg_replace(
            "/name\s*=\s*['\"]" . preg_quote($old, '/') . "['\"]/",
            "name=\"{$new}\"",
            $content
        );
        
        // Update request->input('old') to request->input('new')
        $content = preg_replace(
            "/request\s*->\s*input\s*\(\s*['\"]" . preg_quote($old, '/') . "['\"]\s*\)/",
            "request->input('{$new}')",
            $content
        );
        
        // Update $request->old to $request->new
        $content = preg_replace(
            "/\\\$request\s*->\s*" . preg_quote($old, '/') . "\b/",
            "\$request->{$new}",
            $content
        );
    }
    
    return $content;
}

function processFile($filePath, $routeMapping, $parameterMapping) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    $updatedContent = updateRouteReferences($content, $routeMapping, $parameterMapping);
    
    if ($updatedContent !== $originalContent) {
        file_put_contents($filePath, $updatedContent);
        echo "Updated: {$filePath}\n";
        return true;
    }
    
    return false;
}

function scanDirectory($directory, $extensions, $routeMapping, $parameterMapping) {
    if (!is_dir($directory)) {
        echo "Directory not found: {$directory}\n";
        return;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory)
    );
    
    $updatedFiles = 0;
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $extension = $file->getExtension();
            $fileName = $file->getFilename();
            
            // Check if file has target extension
            $isTargetFile = false;
            foreach ($extensions as $ext) {
                if ($extension === $ext || str_ends_with($fileName, '.' . $ext)) {
                    $isTargetFile = true;
                    break;
                }
            }
            
            if ($isTargetFile) {
                if (processFile($file->getPathname(), $routeMapping, $parameterMapping)) {
                    $updatedFiles++;
                }
            }
        }
    }
    
    echo "Updated {$updatedFiles} files in {$directory}\n";
}

// Main execution
echo "🔄 Starting route update to Indonesian...\n\n";

foreach ($directories as $directory) {
    echo "📁 Processing directory: {$directory}\n";
    scanDirectory($directory, $extensions, $routeMapping, $parameterMapping);
    echo "\n";
}

echo "✅ Route update completed!\n";
echo "\n📋 Summary of changes:\n";
echo "- Updated route() calls to use Indonesian names\n";
echo "- Updated form parameter names (search → cari, etc.)\n";
echo "- Updated request->input() calls\n";
echo "- Updated \$request-> property access\n";
echo "\n⚠️  Please review the changes and test your application!\n";
