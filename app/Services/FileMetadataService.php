<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class FileMetadataService
{
    public function exif(string $fullFilepath): array
    {
        $fp = fopen($fullFilepath, 'rb');

        if (!$fp) {
            Log::warning("Unable to open file for exif metadata extraction");

            return [];
        }

        try {
            $headers = exif_read_data($fp);

            if (!$headers) {
                Log::warning("Unable to read exif headers");

                return [];
            }

            return $headers;
        } finally {
            fclose($fp);
        }
    }
}
