<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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

        return view('admin.settings.database', compact('dbInfo'));
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
}
