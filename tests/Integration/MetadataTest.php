<?php

namespace Tests\Integration;

use App\Services\FileMetadataService;
use Tests\TestCase;

class MetadataTest extends TestCase
{
    /**
     * @dataProvider validExifFilesProvider
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

    /**
     * @dataProvider validIptcFilesProvider
     * @param string $filename
     * @return void
     */
    public function test_that_file_has_iptc_metadata(string $filename): void
    {
        // Given
        $filepath = base_path('/tests/Files/' . $filename);

        // When
        $iptcHeaders = app(FileMetadataService::class)->iptc($filepath);

        // Then
        $this->assertNotEmpty($iptcHeaders);
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

    public static function validIptcFilesProvider(): array
    {
        return [
            ['600x800.jpg'],
        ];
    }

    public static function validExifFilesProvider(): array
    {
        return [
            ['600x800.jpg'],
            ['600x800.tif'],
        ];
    }
}
