<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Setting;

class DatabaseSettingController extends Controller
{
    public function index()
    {
        // Get database info
        $dbInfo = [
            'database_name' => config('database.connections.mysql.database'),
            'database_size' => $this->getDatabaseSize(),
            'total_tables' => $this->getTotalTables(),
            'cache_status' => $this->getCacheStatus(),
        ];

        // Check maintenance mode
        $maintenanceMode = app()->isDownForMaintenance();

        // Get current secret key if maintenance mode is active
        $currentSecret = null;
        if ($maintenanceMode && Storage::exists('maintenance_secret.txt')) {
            $currentSecret = Storage::get('maintenance_secret.txt');
        }

        return view('admin.settings.database', compact('dbInfo', 'maintenanceMode', 'currentSecret'));
    }

    public function clearCache(Request $request)
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->back()->with('success', 'Cache berhasil dibersihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    public function optimize(Request $request)
    {
        try {
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');

            return redirect()->back()->with('success', 'Sistem berhasil dioptimasi!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengoptimasi sistem: ' . $e->getMessage());
        }
    }

    public function optimizeDatabase(Request $request)
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $databaseName = config('database.connections.mysql.database');

            foreach ($tables as $table) {
                $tableName = $table->{"Tables_in_{$databaseName}"};
                DB::statement("OPTIMIZE TABLE `{$tableName}`");
            }

            return redirect()->back()->with('success', 'Database berhasil dioptimasi!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengoptimasi database: ' . $e->getMessage());
        }
    }

    private function getDatabaseSize()
    {
        try {
            $databaseName = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$databaseName]);

            return $result[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTotalTables()
    {
        try {
            $databaseName = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT COUNT(*) as total_tables
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$databaseName]);

            return $result[0]->total_tables ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCacheStatus()
    {
        $cacheFiles = [
            'config' => file_exists(base_path('bootstrap/cache/config.php')),
            'routes' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'views' => is_dir(storage_path('framework/views')) && count(glob(storage_path('framework/views/*'))) > 0,
        ];

        return $cacheFiles;
    }

    public function resetDatabase(Request $request)
    {
        $request->validate([
            'confirmation_1' => 'required|in:RESET',
            'confirmation_2' => 'required|in:DATABASE',
            'confirmation_3' => 'required|in:CONFIRM',
            'admin_password' => 'required',
        ]);

        // Verify admin password
        if (!Hash::check($request->admin_password, auth()->user()->password)) {
            return back()->withErrors(['admin_password' => 'Password admin tidak valid']);
        }

        try {
            DB::beginTransaction();

            // Store admin users before reset
            $adminUsers = User::where('role', 'admin')->get();

            // Clear all tables except migrations
            $this->clearAllTables();

            // Recreate admin users
            foreach ($adminUsers as $admin) {
                User::create([
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'email_verified_at' => now(),
                    'password' => $admin->password,
                    'role' => 'admin',
                    'status' => 'active',
                    'balance' => 0,
                ]);
            }

            // Reset settings to default
            $this->resetSettings();

            // Clear storage files
            $this->clearStorageFiles();

            DB::commit();

            return redirect()->route('admin.pengaturan.database.indeks')
                ->with('success', 'Database berhasil direset! Semua data telah dihapus kecuali akun admin.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal reset database: ' . $e->getMessage()]);
        }
    }

    public function enableMaintenance(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:255',
            'admin_password' => 'required',
        ]);

        // Verify admin password
        if (!Hash::check($request->admin_password, auth()->user()->password)) {
            return back()->withErrors(['admin_password' => 'Password admin tidak valid']);
        }

        try {
            $secret = 'admin-secret-' . now()->timestamp;

            // Store custom message to file if provided
            if ($request->message) {
                Storage::put('maintenance_message.txt', $request->message);
            } else {
                Storage::delete('maintenance_message.txt');
            }

            // Store secret key to file for reference
            Storage::put('maintenance_secret.txt', $secret);

            // Put application in maintenance mode
            Artisan::call('down', [
                '--retry' => 60,
                '--secret' => $secret,
            ]);

            $bypassUrl = url('/?secret=' . $secret);

            return redirect()->route('admin.pengaturan.database.indeks')
                ->with('success', 'Mode maintenance berhasil diaktifkan!')
                ->with('bypass_url', $bypassUrl)
                ->with('secret_key', $secret);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengaktifkan maintenance mode: ' . $e->getMessage()]);
        }
    }

    public function disableMaintenance(Request $request)
    {
        $request->validate([
            'admin_password' => 'required',
        ]);

        // Verify admin password
        if (!Hash::check($request->admin_password, auth()->user()->password)) {
            return back()->withErrors(['admin_password' => 'Password admin tidak valid']);
        }

        try {
            Artisan::call('up');

            // Remove maintenance files
            Storage::delete('maintenance_message.txt');
            Storage::delete('maintenance_secret.txt');

            return redirect()->route('admin.pengaturan.database.indeks')
                ->with('success', 'Mode maintenance berhasil dinonaktifkan.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menonaktifkan maintenance mode: ' . $e->getMessage()]);
        }
    }

    private function clearAllTables()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get all tables except migrations
        $tables = DB::select('SHOW TABLES');
        $databaseName = DB::getDatabaseName();
        $tableKey = 'Tables_in_' . $databaseName;

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // Skip migrations and other system tables
            if (!in_array($tableName, ['migrations', 'failed_jobs', 'password_reset_tokens'])) {
                DB::table($tableName)->truncate();
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function resetSettings()
    {
        // Create default settings
        $defaultSettings = [
            'site_name' => 'CekPenyewa.com',
            'site_tagline' => 'Sistem Blacklist Rental Indonesia',
            'hero_title' => 'Lindungi Bisnis Rental Anda',
            'hero_subtitle' => 'Cek data blacklist pelanggan sebelum menyewakan barang. 100% Gratis untuk pengusaha rental!',
            'meta_title' => 'CekPenyewa.com - Sistem Blacklist Rental Indonesia',
            'meta_description' => 'Sistem blacklist rental terpercaya di Indonesia. Cek data pelanggan bermasalah sebelum menyewakan barang Anda. Gratis untuk pengusaha rental.',
            'meta_keywords' => 'blacklist rental, rental indonesia, cek pelanggan rental, sistem blacklist, rental bermasalah',
            'contact_email' => 'support@cekpenyewa.com',
            'contact_phone' => '+62 21 1234 5678',
            'whatsapp_number' => '6281234567890',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }
    }

    private function clearStorageFiles()
    {
        // Clear public storage directories
        $directories = [
            'blacklist',
            'reports',
            'topup',
            'sponsors',
            'temp',
        ];

        foreach ($directories as $directory) {
            if (Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
        }
    }
}
