<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class ImageRequest extends FormRequest
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

    const MIN_IMAGE_WIDTH = 500;
    const MIN_IMAGE_HEIGHT = 500;
    const MAX_SIZE_IN_BYTES = 5 * 1024 * 1024;
    const MAX_SIZE_IN_KILOBYTES = self::MAX_SIZE_IN_BYTES / 1024;
}
