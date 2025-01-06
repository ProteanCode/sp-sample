<?php

namespace Tests\Integration;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\ImageRequest\StoreImageRequest;
use App\Models\Image;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FakeFileUploadTest extends TestCase
{
    use DatabaseTransactions;

    public function test_that_owner_data_has_to_be_present(): void
    {
        // Given
        Sanctum::actingAs($this->getUser());
        $fakeImage = Image::factory()->makeOne();
        $file = UploadedFile::fake()->image($fakeImage->filename . '.' . $fakeImage->extension, 600, 800);

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $file,
            'data' => [
                'name' => $fakeImage->filename,
            ]
        ]);

        // Then
        $response->assertStatus(422);
        $response->assertSeeText("The data.owner.name field is required");
    }

    public function test_that_file_upload_with_fishy_mimetype_fails(): void
    {
        // Given
        Sanctum::actingAs($this->getUser());
        $validExtensionImage = Image::factory()->makeOne();
        $fishyMimetype = MimeType::fromFilename('not_a_virus.exe');
        $invalidMimetypeFile = UploadedFile::fake()->image(
            $validExtensionImage->filename . '.' . $validExtensionImage->extension
        )->mimeType($fishyMimetype);

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $invalidMimetypeFile,
            'data' => [
                'name' => $validExtensionImage->filename,
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(422);
        $response->assertSeeText("The file field must be a file of type");
    }

    public function test_that_file_upload_with_exact_fit_size_is_successful(): void
    {
        // Given
        Sanctum::actingAs($this->getUser());
        $fakeImage = Image::factory()->makeOne();
        $file = UploadedFile::fake()->image(
            $fakeImage->filename . '.' . $fakeImage->extension, 600, 800
        )->size(StoreImageRequest::MAX_SIZE_IN_KILOBYTES);

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $file,
            'data' => [
                'name' => $fakeImage->filename,
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(200);
    }

    public function test_that_file_upload_with_exceeded_size_fails(): void
    {
        // Given
        Sanctum::actingAs($this->getUser());
        $fakeImage = Image::factory()->makeOne();
        $file = UploadedFile::fake()->image(
            $fakeImage->filename . '.' . $fakeImage->extension
        )->size(StoreImageRequest::MAX_SIZE_IN_KILOBYTES + 1);

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $file,
            'data' => [
                'name' => $fakeImage->filename,
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(422);
        $response->assertSeeText("The file field must not be greater than " . StoreImageRequest::MAX_SIZE_IN_KILOBYTES . " kilobytes.");
    }

    public function test_that_file_upload_with_exceeded_dimensions_fails(): void
    {
        // Given
        Sanctum::actingAs($this->getUser());
        $fakeImage = Image::factory()->tooSmall()->makeOne();
        $file = UploadedFile::fake()->image(
            $fakeImage->filename . '.' . $fakeImage->extension,
            $fakeImage->width,
            $fakeImage->height
        );

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $file,
            'data' => [
                'name' => $fakeImage->filename,
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(422);
        $response->assertSeeText("The file field has invalid image dimensions");
    }

    /**
     * @dataProvider invalidExtensionProvider
     * @return void
     */
    public function test_that_file_upload_with_invalid_extension_fails(string $invalidExtension): void
    {
        // Given
        Sanctum::actingAs($this->getUser());
        $fakeImage = Image::factory()->makeOne(['extension' => $invalidExtension]);
        $file = UploadedFile::fake()->image($fakeImage->filename . '.' . $fakeImage->extension, 600, 800);

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $file,
            'data' => [
                'name' => $fakeImage->filename,
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(422);
        $response->assertSeeText("The file field must have one of the following extensions");
    }

    /**
     * @dataProvider validExtensionProvider
     * @return void
     */
    public function test_that_file_uploads_successfully(string $validExtension): void
    {
        // Given
        Sanctum::actingAs($this->getUser());
        $fakeImage = Image::factory()->makeOne(['extension' => $validExtension]);
        $file = UploadedFile::fake()->image($fakeImage->filename . '.' . $fakeImage->extension, 600, 800);

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $file,
            'data' => [
                'name' => $fakeImage->filename,
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(200);
    }

    public static function validExtensionProvider(): array
    {
        return [
            [ImageRequest::EXT_JPG],
            [ImageRequest::EXT_JPEG],
            [ImageRequest::EXT_PNG],
            [ImageRequest::EXT_TIF],
            [ImageRequest::EXT_TIFF],
            [ImageRequest::EXT_WEBP],
            [ImageRequest::EXT_BMP],
        ];
    }

    public static function invalidExtensionProvider(): array
    {
        return [
            ['bin'],
            ['exe'],
            ['sh'],
            ['php'],
        ];
    }
}
