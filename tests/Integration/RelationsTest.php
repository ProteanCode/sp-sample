<?php

namespace Tests\Integration;

use App\Models\Author;
use App\Models\Image;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RelationsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Each test has a boilerplate of fake data, it could
        // be seeded if separate database for testing would
        // be created and RefreshDatabase trait would be used
        Image::factory()->createMany(20);
        Author::factory()->createMany(40);
    }

    public function test_image_may_have_many_authors(): void
    {
        // Given
        $image = Image::factory()->create();
        $authors = Author::factory()->createMany(2);
        $authorIds = $authors->pluck('id')->toArray();

        // When
        $image->authors()->attach($authors);

        // Then
        $this->assertEquals(2, $image->authors()->count());
        $image->authors->each(fn(Author $author) => $this->assertTrue(
            in_array($author->id, $authorIds)
        ));
    }

    public function test_author_may_have_many_images(): void
    {
        // Given
        $author = Author::factory()->create();
        $images = Image::factory()->createMany(3);
        $imageIds = $images->pluck('id')->toArray();

        // When
        $author->images()->attach($images);

        // Then
        $this->assertEquals(3, $author->images()->count());
        $author->images->each(fn(Image $image) => $this->assertTrue(
            in_array($image->id, $imageIds)
        ));
    }
}
