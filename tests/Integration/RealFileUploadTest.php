<?php

namespace Tests\Integration;

use App\Models\Image;
use App\Repositories\LocalImageRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;
use Tests\Traits\DatabaseMigrationsWithSeed;

class RealFileUploadTest extends TestCase
{
    use DatabaseMigrationsWithSeed;

    protected function tearDownTheTestEnvironment(): void
    {
        Storage::disk()->deleteDirectory('images');
    }

    /**
     * @dataProvider validFilesProvider
     * @param string $filename
     * @return void
     */
    public function test_that_real_file_rollbacks_successfully(string $filename)
    {
        $scaleDownMockFor128Thumbnail = Mockery::mock((new LocalImageRepository()), function (MockInterface $mock) {
            $mock->shouldReceive('scaleDown')
                ->withArgs(fn(...$args) => $args[2] === 128)
                ->andReturnUsing(fn() => throw new RuntimeException("Rollback mock"))->once();
        })->makePartial();

        $this->instance(LocalImageRepository::class, $scaleDownMockFor128Thumbnail);

        // Given
        $filepath = base_path('/tests/Files/' . $filename);
        $uploadedFile = UploadedFile::fake()->createWithContent($filename, file_get_contents($filepath));

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $uploadedFile,
            'data' => [
                'name' => $uploadedFile->getFilename(),
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(400);

        $this->assertEquals(0, Image::query()->count());
    }

    /**
     * @dataProvider validFilesProvider
     * @param string $filename
     * @return void
     */
    public function test_that_real_file_uploads_successfully(string $filename)
    {
        // Given
        $filepath = base_path('/tests/Files/' . $filename);
        $uploadedFile = UploadedFile::fake()->createWithContent($filename, file_get_contents($filepath));

        // When
        $response = $this->json('POST', route('images.store'), [
            'file' => $uploadedFile,
            'data' => [
                'name' => $uploadedFile->getFilename(),
                'owner' => [
                    'name' => 'John Doe',
                    'email' => 'j.doe@cyxcvnvwd.org'
                ]
            ]
        ]);

        // Then
        $response->assertStatus(200);
    }

    public static function validFilesProvider(): array
    {
        return [
            ['600x800.bmp'],
            ['600x800.jpg'],
            ['600x800.png'],
            ['600x800.tif'],
            ['600x800.webp'],
        ];
    }
}
