<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Image;

class AuthorImageService
{
    public function bind(Author $author, Image $image): void
    {
        $author->images()->attach($image);
    }
}
