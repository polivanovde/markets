<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string author
 * @property string email
 */
class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'author',
        'email'
    ];

    /**
     * @return HasMany
     */
    public function post(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
