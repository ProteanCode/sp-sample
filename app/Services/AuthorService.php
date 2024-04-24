<?php

namespace App\Services;

use App\Models\Author;

class AuthorService
{
    public function getOrCreate(string $name, string $email): Author
    {
        $author = Author::query()
            ->where('name', $name)
            ->where('email', $email)
            ->first();

        if (!$author) {
            return Author::query()->create([
                'name' => $name,
                'email' => $email
            ]);
        }

        return $author;
    }
}
