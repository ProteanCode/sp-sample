<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property integer $id
 * @property string $filename
 * @property string $extension
 * @property string $hash
 * @property string $disk
 * @property string $path
 * @property integer $width
 * @property integer $height
 * @property integer $size_in_bytes
 * @property integer|null $image_id
 *
 * @property-read Collection $authors
 * @property-read Collection $children
 * @property-read Image|null $parent
 */
class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'size_in_bytes' => 'integer',
        'image_id' => 'integer',
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class);
    }
}
