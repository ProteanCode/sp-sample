<?php

namespace App\Services;

use App\Enums\IPTC;
use Illuminate\Support\Facades\Log;

class FileMetadataService
{
    public function iptc(string $fullFilepath): array
    {
        $size = getimagesize($fullFilepath, $info);

        if (isset($info["APP13"])) {
            if ($iptc = iptcparse($info["APP13"])) {
                $data = [];

                foreach ($iptc as $key => $values) {
                    $iptcHeader = $this->getIptcHeader($key);
                    if (empty($iptcHeader)) {
                        continue;
                    }

                    $data[$iptcHeader] = last($values);
                }

                return $data;
            }
        }

        return [];
    }

    public function exif(string $fullFilepath): array
    {
        $fp = fopen($fullFilepath, 'rb');

        $mime = mime_content_type($fp);

        if (in_array($mime, ['image/bmp'])) {
            return [];
        }

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

    private function getIptcHeader(string $key): ?string
    {
        $key = last(explode('#', $key));

        return IPTC::tryFrom($key)?->name;
    }
}
