<?php

namespace App\Factories;

use App\Exceptions\UnsupportedImagePersistStrategy;
use App\Strategies\ImagePersister\ImagePersistStrategy;
use App\Strategies\ImagePersister\PNGImagePersistStrategy;
use App\Strategies\ImagePersister\JPGImagePersistStrategy;
use App\Strategies\ImagePersister\WebpImagePersistStrategy;
use App\Strategies\ImagePersister\TiffImagePersistStrategy;
use App\Strategies\ImagePersister\BmpImagePersistStrategy;
use Illuminate\Http\UploadedFile;

class ImageCreatorFactory
{
    public const EXT_PNG = 'png';
    public const EXT_JPG = 'jpg';
    public const EXT_JPEG = 'jpeg';
    public const EXT_TIF = 'tif';
    public const EXT_TIFF = 'tiff';
    public const EXT_WEBP = 'webp';
    public const EXT_BMP = 'bmp';

    public const VALID_EXTENSIONS = [
        self::EXT_PNG,
        self::EXT_JPG,
        self::EXT_JPEG,
        self::EXT_TIF,
        self::EXT_TIFF,
        self::EXT_WEBP,
        self::EXT_BMP,
    ];

    public static function make(UploadedFile $uploadedFile): ImagePersistStrategy
    {
        $extension = $uploadedFile->extension();


        switch (strtolower($extension)) {
            case self::EXT_PNG:
                return new PNGImagePersistStrategy($uploadedFile);
            case self::EXT_JPG:
                return new JPGImagePersistStrategy($uploadedFile);
            case self::EXT_JPEG:
                return new JPGImagePersistStrategy($uploadedFile);
            case self::EXT_WEBP:
                return new WebpImagePersistStrategy($uploadedFile);
            case self::EXT_TIF:
                return new TiffImagePersistStrategy($uploadedFile);
            case self::EXT_TIFF:
                return new TiffImagePersistStrategy($uploadedFile);
            case self::EXT_BMP:
                return new BmpImagePersistStrategy($uploadedFile);
        }

        throw new UnsupportedImagePersistStrategy("Unsupported image creator strategy for extension: " . $extension);
    }
}
