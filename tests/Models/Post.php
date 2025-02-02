<?php

namespace Tofiq\AuditTrail\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tofiq\AuditTrail\Tests\Database\Factories\PostFactory;

class Post extends Model
{
    use HasFactory;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'title',
        'content',
        'published_at',
    ];

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
