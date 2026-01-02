<?php

namespace Modules\DoubleEntry\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use File;

class Pack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'double-entry:pack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pack the Double Entry module into a distributable ZIP file';

    /**
     * Directories and files to exclude from the package.
     *
     * @var array
     */
    protected $exclude = [
        'Tests',
        '.git',
        'node_modules',
        '.env',
        '.gitignore',
        '.DS_Store',
        'Thumbs.db',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modulePath = base_path('modules/DoubleEntry');
        $zipPath = base_path('DoubleEntry.zip');

        $this->info("Packing module from: {$modulePath}");

        if (!extension_loaded('zip')) {
            $this->error('PHP Zip extension is not loaded.');
            return 1;
        }

        if (!is_dir($modulePath)) {
            $this->error("Module directory not found: {$modulePath}");
            return 1;
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            $this->error("Cannot open <{$zipPath}>");
            return 1;
        }

        $files = File::allFiles($modulePath);
        $addedCount = 0;
        $excludedCount = 0;

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $fullPath = $file->getRealPath();
            
            // Check if file should be excluded
            $shouldExclude = false;
            foreach ($this->exclude as $excludePattern) {
                if (strpos($relativePath, $excludePattern) !== false) {
                    $shouldExclude = true;
                    break;
                }
            }
            
            if ($shouldExclude) {
                $excludedCount++;
                continue;
            }

            $zipPathInArchive = 'DoubleEntry/' . $relativePath;
            $zip->addFile($fullPath, $zipPathInArchive);
            $addedCount++;
        }

        $zip->close();

        $this->info("Module successfully packed to: {$zipPath}");
        $this->info("Added {$addedCount} files, excluded {$excludedCount} files.");

        return 0;
    }
}
