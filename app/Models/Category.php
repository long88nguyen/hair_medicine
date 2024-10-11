<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "image",
        "slug",
        "tags",
        "meta_title",
        "meta_keywords",
        "meta_description",
        "canonical_url",
        "deleteted_at",
        "created_at",
        "updated_at",
    ];
}
