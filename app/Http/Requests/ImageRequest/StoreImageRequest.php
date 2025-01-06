<?php

namespace App\Http\Requests\ImageRequest;

use App\Http\Requests\ImageRequest;
use App\Http\Rules\ExtendedImageRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class StoreImageRequest extends ImageRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

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
                    ->extensions(self::VALID_EXTENSIONS)
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
