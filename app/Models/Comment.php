<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['description','post_id','user_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
