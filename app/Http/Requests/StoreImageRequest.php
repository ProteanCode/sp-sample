<?php

namespace App\Http\Requests;

use App\Factories\ImageCreatorFactory;
use App\Http\Rules\ExtendedImageRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class StoreImageRequest extends FormRequest
{
    const MIN_IMAGE_WIDTH = 500;
    const MIN_IMAGE_HEIGHT = 500;
    const MAX_SIZE_IN_BYTES = 5 * 1024 * 1024;
    const MAX_SIZE_IN_KILOBYTES = self::MAX_SIZE_IN_BYTES / 1024;

    public function getImageFile(): UploadedFile
    {
        return $this->file;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                (new ExtendedImageRule())
                    ->extensions(ImageCreatorFactory::VALID_EXTENSIONS)
                    ->max(self::MAX_SIZE_IN_KILOBYTES)
                    ->dimensions(Rule::dimensions()
                        ->minWidth(self::MIN_IMAGE_WIDTH)
                        ->minWidth(self::MIN_IMAGE_HEIGHT)
                    ),
            ],
            'data.name' => ['required', 'string'],
            'data.owner.name' => ['required', 'string'],
            'data.owner.email' => ['required', 'email'],
        ];
    }
}
