<?php

namespace App\Repositories;

use App\Exceptions\InvalidImageSizeException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class LocalImageRepository
{
    private Filesystem $disk;
    private string $diskName;

    public function __construct()
    {
        $this->diskName = app()->runningUnitTests() ? 'testing' : config('filesystems.default');
        $this->disk = Storage::disk($this->diskName);
    }

    public function getFullPath(string $file)
    {
        return $this->disk->path($file);
    }

    public function getDiskName(): string
    {
        return $this->diskName;
    }

    public function deleteTree(int $id): bool
    {
        return $this->disk->deleteDirectory('/images/' . $id);
    }

    public function saveUploadedFile(UploadedFile $uploadedFile, int $id): string
    {
        return $this->disk->putFile('/images/' . $id, $uploadedFile);
    }

    public function getImageDimensions(string $filePath): array
    {
        $info = getimagesize($filePath);

        if (!$info) {
            throw new InvalidImageSizeException("Could not get the image size for: " . $filePath);
        }

        [$width, $height] = $info;

        return [$width, $height];
    }

    public function scaleDown(string $sourceFileFullPath, string $targetRelativePath, int $targetWidth): void
    {
        $targetRelativeDir = pathinfo($targetRelativePath)['dirname'];

        $manager = new ImageManager(new Driver());
        $image = $manager->read($sourceFileFullPath);

        if ($this->disk->makeDirectory($targetRelativeDir)) {
            (clone $image)->scaleDown($targetWidth)->save(
                $this->disk->path($targetRelativePath)
            );
        }
    }
}
