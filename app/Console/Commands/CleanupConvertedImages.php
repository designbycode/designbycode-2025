<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanupConvertedImages extends Command
{
    protected $signature = 'images:cleanup {--hours=24 : Hours after which to delete converted images}';
    protected $description = 'Clean up old converted images from storage';

    public function handle(): int
    {
        $hours = (int)$this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);

        $convertedPath = storage_path('app/public/converted');

        if (!File::exists($convertedPath)) {
            $this->info('Converted images directory does not exist.');
            return self::SUCCESS;
        }

        $files = File::files($convertedPath);
        $deletedCount = 0;

        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(File::lastModified($file));

            if ($fileTime->lt($cutoffTime)) {
                File::delete($file);
                $deletedCount++;
            }
        }

        $this->info("Cleaned up {$deletedCount} old converted images.");

        return self::SUCCESS;
    }
}
