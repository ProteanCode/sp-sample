<?php

namespace App\Http\Requests\ImageRequest;

use App\Http\Requests\ImageRequest;

class CreateImageRequest extends ImageRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
