<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    public function definition(): array
    {
        $path = fake()->filePath();
        $pathInfo = pathinfo($path);

        $width = fake()->numberBetween(100, 500);
        $height = fake()->numberBetween(100, 500);

        return [
            'filename' => $pathInfo['filename'],
            'dir' => $pathInfo['dirname'],
            'width' => $width,
            'height' => $height,
            'size_in_bytes' => $width * $height,
            'image_id' => null
        ];
    }
}
