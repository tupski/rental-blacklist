<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:reset {--force : Force reset without confirmation} {--seed : Run seeders after reset}';

    /**
     * The console command description.
     */
    protected $description = 'Reset database completely - drop all tables, run migrations, and optionally seed data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $environment = app()->environment();
        
        // Safety check for production
        if ($environment === 'production' && !$this->option('force')) {
            $this->error('❌ Cannot reset database in production environment!');
            $this->info('Use --force flag only if you are absolutely sure.');
            return 1;
        }

        // Confirmation
        if (!$this->option('force')) {
            $this->warn('⚠️  This will completely reset your database!');
            $this->warn('All data will be lost permanently.');
            
            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('Database reset cancelled.');
                return 0;
            }
        }

        $this->info('🔄 Starting database reset...');

        try {
            // Step 1: Clear uploaded files
            $this->clearUploadedFiles();

            // Step 2: Drop all tables
            $this->dropAllTables();

            // Step 3: Run migrations
            $this->info('📋 Running migrations...');
            Artisan::call('migrate', ['--force' => true]);
            $this->info('✅ Migrations completed');

            // Step 4: Run seeders if requested
            if ($this->option('seed')) {
                $this->info('🌱 Running seeders...');
                Artisan::call('db:seed', ['--force' => true]);
                $this->info('✅ Seeders completed');
            }

            // Step 5: Clear caches
            $this->clearCaches();

            $this->info('🎉 Database reset completed successfully!');
            
            if ($environment === 'production') {
                $this->warn('⚠️  Production database has been reset!');
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Database reset failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clear all uploaded files
     */
    protected function clearUploadedFiles()
    {
        $this->info('🗑️  Clearing uploaded files...');
        
        $directories = [
            'public/bukti',
            'public/foto-penyewa', 
            'public/foto-ktp-sim',
            'public/bukti-pendukung',
            'public/guest-reports',
            'public/test-files',
            'public/test-images',
            'public/test-videos',
            'public/test-docs'
        ];

        foreach ($directories as $directory) {
            if (Storage::exists($directory)) {
                Storage::deleteDirectory($directory);
                $this->line("   Cleared: {$directory}");
            }
        }

        $this->info('✅ Uploaded files cleared');
    }

    /**
     * Drop all tables from database
     */
    protected function dropAllTables()
    {
        $this->info('🗃️  Dropping all tables...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get all table names
        $tables = DB::select('SHOW TABLES');
        $databaseName = DB::getDatabaseName();
        $tableKey = "Tables_in_{$databaseName}";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
            $this->line("   Dropped: {$tableName}");
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('✅ All tables dropped');
    }

    /**
     * Clear application caches
     */
    protected function clearCaches()
    {
        $this->info('🧹 Clearing caches...');

        $commands = [
            'cache:clear' => 'Application cache',
            'config:clear' => 'Configuration cache',
            'route:clear' => 'Route cache',
            'view:clear' => 'View cache'
        ];

        foreach ($commands as $command => $description) {
            try {
                Artisan::call($command);
                $this->line("   Cleared: {$description}");
            } catch (\Exception $e) {
                $this->line("   Warning: Could not clear {$description}");
            }
        }

        $this->info('✅ Caches cleared');
    }
}
