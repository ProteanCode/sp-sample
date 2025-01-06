<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Author;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageAuthorsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $images = Image::factory()->createMany(10);
        $authors = Author::factory()->createMany(20);

        foreach ($images as $image) {
            $pick = rand(0, 5);
            foreach ($authors->random($pick) as $author) {
                $image->authors()->attach($author);
            }
        }
    }
}
