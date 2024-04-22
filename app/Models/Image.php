<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['*'];
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
}
