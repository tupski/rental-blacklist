<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RentalBlacklist;
use App\Models\GuestReport;
use App\Traits\HandlesFileWatermark;

class ProcessExistingFilesWatermark extends Command
{
    use HandlesFileWatermark;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watermark:process-existing {--force : Force reprocess existing watermarks}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process existing files to add watermarks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing existing files for watermarks...');

        // Process RentalBlacklist files
        $this->info('Processing RentalBlacklist files...');
        $blacklists = RentalBlacklist::whereNotNull('bukti')->get();

        foreach ($blacklists as $blacklist) {
            if ($blacklist->bukti && is_array($blacklist->bukti)) {
                $this->info("Processing blacklist ID: {$blacklist->id}");
                $this->reprocessWatermarks($blacklist);
            }
        }

        // Process GuestReport files
        $this->info('Processing GuestReport files...');
        $reports = GuestReport::whereNotNull('bukti')->get();

        foreach ($reports as $report) {
            if ($report->bukti && is_array($report->bukti)) {
                $this->info("Processing guest report ID: {$report->id}");
                $this->reprocessWatermarks($report);
            }
        }

        $this->info('Watermark processing completed!');
    }
}
