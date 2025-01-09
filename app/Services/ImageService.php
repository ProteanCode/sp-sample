<?php

namespace App\Services;

use App\Models\Image;
use App\Repositories\LocalImageRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;

class ImageService
{
    public function __construct(protected LocalImageRepository $imageRepository)
    {

    }

    public function listAll(): LengthAwarePaginator
    {
        return $this->imageRepository->getPaginator();
    }

    public function create(UploadedFile $uploadedFile): Image
    {
        [$width, $height] = $this->imageRepository->getImageDimensions($uploadedFile->getRealPath());

        $parent = $this->createImageInDatabase($uploadedFile);

        $file = $this->imageRepository->saveUploadedFile($uploadedFile, $parent->getKey(), $parent->hash);

        if (!$file) {
            throw new RuntimeException("Failed to save uploaded file: " . $uploadedFile->getRealPath());
        }

        $fullPath = $this->imageRepository->getFullPath($file);

        $sizeVariants = $this->getImageVariants();

        foreach ($sizeVariants as $variantName => $width) {
            $relativePath = '/images/' . $parent->id . '/' . $variantName . '/' . basename($fullPath);

            $this->imageRepository->scaleDown(
                $fullPath,
                $relativePath,
                $width
            );

            $this->createImageInDatabase($uploadedFile, $parent, $relativePath);
        }

        return $parent;
    }

    public function rollback(UploadedFile $uploadedFile): bool
    {
        $parent = Image::whereNull('image_id')
            ->where('hash', $this->makeFileHash($uploadedFile->getRealPath()))
            ->first();

        if (!$parent) {
            return false;
        }

        return $this->imageRepository->deleteTree($parent->getKey());
    }

    protected function createImageInDatabase(
        UploadedFile $uploadedFile,
        Image        $parent = null,
        string       $thumbnailPath = null
    ): Image
    {
        if ($parent !== null && $thumbnailPath === null) {
            throw new InvalidArgumentException("Thumbnail path is required when creating child image");
        }

        $path = $thumbnailPath ? $this->imageRepository->getFullPath($thumbnailPath) : $uploadedFile->getRealPath();

        [$height, $width] = $this->imageRepository->getImageDimensions($path);

        $data = [
            'disk' => $this->imageRepository->getDiskName(),
            'filename' => $uploadedFile->getClientOriginalName(),
            'extension' => $uploadedFile->getClientOriginalExtension(),
            'path' => '/images',
            'width' => $width,
            'height' => $height,
            'size_in_bytes' => filesize($path),
        ];

        if ($parent) {
            $data['hash'] = $this->makeFileHash(
                Storage::disk($this->imageRepository->getDiskName())->path($thumbnailPath)
            );

            return $parent->children()->create($data);
        }

        $data['hash'] = $this->makeFileHash($uploadedFile->getRealPath());

        return Image::create($data);
    }

    /**
     * @return array<string, int>
     */
    protected function getImageVariants(): array
    {
        return [
            'sm' => 64,
            'md' => 128,
            'lg' => 256,
        ];
    }

    protected function makeFileHash(string $absoluteFilePath): string
    {
        return hash_file('haval160,4', $absoluteFilePath);
    }
}
