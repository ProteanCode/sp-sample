<?php

namespace Database\Factories;

use App\Http\Requests\StoreImageRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    public function definition(): array
    {
        $path = $this->makeFilePath();
        $pathInfo = pathinfo($path);

        $width = fake()->numberBetween(500, 800);
        $height = fake()->numberBetween(500, 800);

        return [
            'filename' => $pathInfo['filename'],
            'extension' => $pathInfo['extension'],
            'disk' => 'testing',
            'hash' => fake()->unique()->hexColor(),
            'path' => $pathInfo['dirname'],
            'width' => $width,
            'height' => $height,
            'size_in_bytes' => $width * $height,
            'image_id' => null
        ];
    }

    public function unsupported(): static
    {
        $path = $this->makeUnsupportedFilePath();
        $pathInfo = pathinfo($path);

        return $this->state(fn(array $attributes) => [
            'filename' => $pathInfo['filename'],
            'dir' => $pathInfo['dirname'],
            'extension' => $pathInfo['extension'],
        ]);
    }

    public function tooSmall(): static
    {
        $width = fake()->numberBetween(10, (StoreImageRequest::MIN_IMAGE_WIDTH) - 1);
        $height = fake()->numberBetween(10, (StoreImageRequest::MIN_IMAGE_HEIGHT) - 1);

        return $this->state(fn(array $attributes) => [
            'width' => $width,
            'height' => $height,
            'size_in_bytes' => $width * $height,
        ]);
    }

    private function makeExtension(): string
    {
        return fake()->randomElement([
            'webp', 'png', 'jpg', 'jpeg', 'tiff', 'bmp'
        ]);
    }

    private function makeFilePath(): string
    {
        return '/tmp/' . fake()->colorName . '.' . $this->makeExtension();
    }

    private function makeUnsupportedFilePath(): string
    {
        return '/tmp/' . fake()->colorName . '.bin';
    }
}
