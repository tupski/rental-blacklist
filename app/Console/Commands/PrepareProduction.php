<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PrepareProduction extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:prepare-production {--force : Force preparation without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Prepare application for production deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $environment = app()->environment();
        
        if ($environment !== 'production' && !$this->option('force')) {
            $this->warn('⚠️  This command is intended for production environment.');
            if (!$this->confirm('Continue anyway?')) {
                return 0;
            }
        }

        $this->info('🚀 Preparing application for production...');

        try {
            // Step 1: Check environment file
            $this->checkEnvironmentFile();

            // Step 2: Optimize application
            $this->optimizeApplication();

            // Step 3: Set up storage
            $this->setupStorage();

            // Step 4: Run migrations
            $this->runMigrations();

            // Step 5: Security checks
            $this->performSecurityChecks();

            // Step 6: Final verification
            $this->performFinalVerification();

            $this->info('🎉 Application is ready for production!');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Production preparation failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Check and validate environment file
     */
    protected function checkEnvironmentFile()
    {
        $this->info('🔍 Checking environment configuration...');

        if (!File::exists(base_path('.env'))) {
            throw new \Exception('.env file not found');
        }

        $requiredVars = [
            'APP_NAME',
            'APP_ENV',
            'APP_KEY',
            'APP_URL',
            'DB_CONNECTION',
            'DB_HOST',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD'
        ];

        $missing = [];
        foreach ($requiredVars as $var) {
            if (empty(env($var))) {
                $missing[] = $var;
            }
        }

        if (!empty($missing)) {
            throw new \Exception('Missing required environment variables: ' . implode(', ', $missing));
        }

        // Check if APP_ENV is production
        if (env('APP_ENV') !== 'production') {
            $this->warn('⚠️  APP_ENV is not set to "production"');
        }

        // Check if APP_DEBUG is false
        if (env('APP_DEBUG', false)) {
            $this->warn('⚠️  APP_DEBUG should be false in production');
        }

        $this->info('✅ Environment configuration checked');
    }

    /**
     * Optimize application for production
     */
    protected function optimizeApplication()
    {
        $this->info('⚡ Optimizing application...');

        $commands = [
            'config:cache' => 'Configuration cache',
            'route:cache' => 'Route cache',
            'view:cache' => 'View cache',
            'event:cache' => 'Event cache'
        ];

        foreach ($commands as $command => $description) {
            try {
                Artisan::call($command);
                $this->line("   Cached: {$description}");
            } catch (\Exception $e) {
                $this->warn("   Warning: Could not cache {$description}: " . $e->getMessage());
            }
        }

        $this->info('✅ Application optimized');
    }

    /**
     * Set up storage directories and permissions
     */
    protected function setupStorage()
    {
        $this->info('📁 Setting up storage...');

        // Create storage link
        if (!File::exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $this->line('   Created storage link');
        }

        // Create required directories
        $directories = [
            'bukti',
            'foto-penyewa',
            'foto-ktp-sim', 
            'bukti-pendukung',
            'watermarked'
        ];

        foreach ($directories as $directory) {
            $path = "public/{$directory}";
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path);
                $this->line("   Created directory: {$directory}");
            }
        }

        $this->info('✅ Storage setup completed');
    }

    /**
     * Run database migrations
     */
    protected function runMigrations()
    {
        $this->info('📋 Running database migrations...');

        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->info('✅ Migrations completed');
        } catch (\Exception $e) {
            throw new \Exception('Migration failed: ' . $e->getMessage());
        }
    }

    /**
     * Perform security checks
     */
    protected function performSecurityChecks()
    {
        $this->info('🔒 Performing security checks...');

        $checks = [];

        // Check APP_KEY
        if (empty(env('APP_KEY'))) {
            $checks[] = 'APP_KEY is not set';
        }

        // Check database connection
        try {
            \DB::connection()->getPdo();
            $this->line('   ✅ Database connection successful');
        } catch (\Exception $e) {
            $checks[] = 'Database connection failed: ' . $e->getMessage();
        }

        // Check storage permissions
        if (!is_writable(storage_path())) {
            $checks[] = 'Storage directory is not writable';
        }

        // Check cache permissions
        if (!is_writable(storage_path('framework/cache'))) {
            $checks[] = 'Cache directory is not writable';
        }

        if (!empty($checks)) {
            $this->warn('⚠️  Security issues found:');
            foreach ($checks as $check) {
                $this->line("   - {$check}");
            }
        } else {
            $this->info('✅ Security checks passed');
        }
    }

    /**
     * Perform final verification
     */
    protected function performFinalVerification()
    {
        $this->info('🔍 Performing final verification...');

        $checks = [
            'Configuration cached' => File::exists(bootstrap_path('cache/config.php')),
            'Routes cached' => File::exists(bootstrap_path('cache/routes-v7.php')),
            'Views cached' => File::exists(storage_path('framework/views')),
            'Storage linked' => File::exists(public_path('storage')),
        ];

        $failed = [];
        foreach ($checks as $check => $passed) {
            if ($passed) {
                $this->line("   ✅ {$check}");
            } else {
                $this->line("   ❌ {$check}");
                $failed[] = $check;
            }
        }

        if (!empty($failed)) {
            $this->warn('⚠️  Some optimizations may not be active');
        }

        $this->info('✅ Final verification completed');
    }
}
