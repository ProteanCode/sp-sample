<?php

namespace App\Repositories;

use App\Exceptions\InvalidImageSizeException;
use App\Models\Image;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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

    public function getBase64Content(string $relativeFilePath): string
    {
        try {
            return base64_encode(file_get_contents($this->getFullPath($relativeFilePath)));
        } catch (\Throwable $exception) {
            return '';
        }
    }

    public function getFullPath(string $file): string
    {
        return $this->disk->path($file);
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return Image::query()
            ->with('children')
            ->whereHas('authors')
            ->with('authors')
            ->orderBy('id', 'desc')
            ->paginate(3)
            ->through(function (Image $image) {
                $path = $image->path . '/' . $image->id . '/' . $image->hash.'.'.$image->extension;

                $image->base64_content = $this->getBase64Content($path);

                return $image;
            });
    }

    public function getDiskName(): string
    {
        return $this->diskName;
    }

    public function deleteTree(int $id): bool
    {
        return $this->disk->deleteDirectory('/images/' . $id);
    }

    public function saveUploadedFile(UploadedFile $uploadedFile, int $id, string $hash): string
    {
        $name = $hash . '.' . $uploadedFile->extension();
        File::makeDirectory(path: 'images/' . $id, recursive: true, force: true);
        return $uploadedFile->storeAs('/images/' . $id, $name);
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
