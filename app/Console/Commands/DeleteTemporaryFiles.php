<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Storage;

class DeleteTemporaryFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-temporary-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary uploaded files older than 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (Storage::disk('temp')->directories() as $dir) {
            $dirLastModified = Carbon::createFromTimestamp(
                Storage::disk('temp')->lastModified($dir)
            );

            if (now()->diffInHours($dirLastModified) > 24) {
                Storage::disk('temp')->deleteDirectory($dir);
            }
        }
    }
}
