<?php

namespace Tests\Integration;

use App\Services\FileMetadataService;
use Tests\TestCase;

class MetadataTest extends TestCase
{
    /**
     * @dataProvider validFilesProvider
     * @param string $filename
     * @return void
     */
    public function test_that_file_has_exif_metadata(string $filename): void
    {
        // Given
        $filepath = base_path('/tests/Files/' . $filename);

        // When
        $exifHeaders = app(FileMetadataService::class)->exif($filepath);

        // Then
        $this->assertNotEmpty($exifHeaders);
    }

    public static function validFilesProvider(): array
    {
        return [
            ['600x800.jpg'],
            ['600x800.bmp'],
            ['600x800.png'],
            ['600x800.tif'],
            ['600x800.webp'],
        ];
    }
}
