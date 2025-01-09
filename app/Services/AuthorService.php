<?php

namespace App\Services;

use App\Models\Author;

class AuthorService
{
    public function getOrCreate(string $name, string $surname): Author
    {
        $author = Author::query()
            ->where('name', $name)
            ->where('surname', $surname)
            ->first();

        if (!$author) {
            return Author::query()->create([
                'name' => $name,
                'surname' => $surname
            ]);
        }

        return $author;
    }
}
