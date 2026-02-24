<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem as BaseFilesystem;

class CustomFilesystem extends BaseFilesystem
{
    /**
     * Replace the given file with a copy.
     *
     * @param  string  $path
     * @param  string  $content
     * @param  int|null  $mode
     * @return void
     */
    public function replace($path, $content, $mode = null)
    {
        // If the path already exists and is a symlink, get the real path...
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        // Create temp directory if it doesn't exist
        $tempDir = dirname($path);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Use a different approach - create temp file with file_put_contents directly
        $tempPath = $tempDir . '/' . uniqid('temp_', true) . '.tmp';
        
        // Write content first (this creates the file)
        file_put_contents($tempPath, $content);
        
        // Set permissions after file is created
        if (!is_null($mode)) {
            chmod($tempPath, $mode);
        } else {
            chmod($tempPath, 0777 - umask());
        }

        rename($tempPath, $path);
    }
}
